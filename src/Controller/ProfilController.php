<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Prestation;
use App\Entity\User;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use DateTime;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/base.html.twig');
    }


    #[Route('/profil/presta/ajouter', name: "app_addPresta")]
    #[Route('/profil/presta/{id}/edit', name: "app_editPresta")]
    public function editPresta(EntityManagerInterface $em, PrestationRepository $prestaRepo, Security $security, Request $request, int $id = null): Response
    {
        $user = $security->getUser();

        if ($request->attributes->get('_route') == 'app_addPresta') {
            $presta = new Prestation();
            $presta->setDateCommande(new DateTime());
        } else {
            $presta = $prestaRepo->find($id);

            if ($presta->getUser() === null || $presta->getUser()->getId() !== $user->getId()) {
                $this->addFlash(
                    'danger',
                    'Vous n\'avez aucun droit sur cette prestation'
                );
                return $this->redirectToRoute('app_profilPrestaList');
            }
        }

        $form = $this->createForm(PrestationType::class, $presta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presta->setUser($user);
            $em->persist($presta);
            $em->flush();
            $this->addFlash(
                'success',
                'Prestation Sauvegardé'
            );
            return $this->redirectToRoute('app_profilPrestaList');
        }

        return $this->render('profil/edit-presta.html.twig', ['form' => $form]);
    }

    #[Route('/profil/presta', name: 'app_profilPrestaList')]
    public function showListPrestaByUser(PrestationRepository $prestaRepo, Security $security): Response
    {
        $AllPrestaByUser = $security->getUser()->getPrestations()->toArray();
        usort($AllPrestaByUser, [$this, 'compareDates']);

        return $this->render('profil/historique.html.twig', ['prestations' => $AllPrestaByUser]);
    }

    #[Route('/profil/presta/{id}/delete', name: 'app_deletePresta')]
    public function deletePrestaUser(int $id, PrestationRepository $prestaRepo, EntityManagerInterface $em, Security $security): Response
    {
        $presta = $prestaRepo->find($id);
        $user = $security->getUser();

        if ($presta->getUser() === null || $presta->getUser()->getId() !== $user->getId()) {
            $this->addFlash(
                'danger',
                'Vous n\'avez aucun droit sur cette prestation'
            );
            return $this->redirectToRoute('app_profilPrestaList');
        }

        $em->remove($presta);
        $em->flush();

        $this->addFlash(
            'success',
            'Prestation Supprimé'
        );
        return $this->redirectToRoute('app_profil');
    }

    function compareDates($prestationA, $prestationB)
    {
        $dateA = $prestationA->getDateCommande();
        $dateB = $prestationB->getDateCommande();

        if ($dateA > $dateB) {
            return -1;
        } elseif ($dateA < $dateB) {
            return 1;
        } else {
            return 0;
        }
    }
}
