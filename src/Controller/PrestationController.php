<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationController extends AbstractController
{
    #[Route('/prestation', name: 'app_prestation')]
    public function index(PrestationRepository $prestaRepo, UserRepository $userRepo): Response
    {
        $prestations = $prestaRepo->findBy([], ['dateCommande' => 'desc']);
        $user = $userRepo->findLatestUser();
        $last_user = strtoupper($user->getNom()) . " " . $user->getPrenom();

        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestations, 'lastUser' => $last_user
        ]);
    }
}
