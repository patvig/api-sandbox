<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(security: "is_granted('ROLE_USER')")]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'string', length: 50)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 50)]
    private $email;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateCommande1;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateLastCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateCommande1(): ?\DateTimeInterface
    {
        return $this->dateCommande1;
    }

    public function setDateCommande1(?\DateTimeInterface $dateCommande1): self
    {
        $this->dateCommande1 = $dateCommande1;

        return $this;
    }

    public function getDateLastCommande(): ?\DateTimeInterface
    {
        return $this->dateLastCommande;
    }

    public function setDateLastCommande(?\DateTimeInterface $dateLastCommande): self
    {
        $this->dateLastCommande = $dateLastCommande;

        return $this;
    }
}