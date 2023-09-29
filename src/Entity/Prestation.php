<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $extrait = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 5,
        max: 500,
        notInRangeMessage: "Montant hors de la plage autorisée. Le montant doit être compris entre {{ min }} et {{ max }}."
    )]
    private ?float $remuneration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column]
    #[Assert\Regex(
        pattern: "/^(04|06|07|09)\d{8}$/",
        message: "Le numéro de téléphone doit commencer par 04, 06, 07 ou 09 et contenir 10 chiffres au total."
    )]
    private ?string $tel = null;

    #[ORM\ManyToOne(inversedBy: 'prestations')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getExtrait(): ?string
    {
        return $this->extrait;
    }

    public function setExtrait(string $extrait): static
    {
        $this->extrait = $extrait;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRemuneration(): ?float
    {
        return $this->remuneration;
    }

    public function setRemuneration(float $remuneration): static
    {
        $this->remuneration = $remuneration;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getUser(): ?User 
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
