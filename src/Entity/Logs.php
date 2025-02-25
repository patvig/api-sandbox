<?php

namespace App\Entity;

use App\Repository\LogsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogsRepository::class)]
class Logs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $methode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $controleur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateExec = null;

    #[ORM\Column(type: 'blob', length: 25000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'blob', length: 25000, nullable: true)]
    private ?string $log = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(?string $methode): static
    {
        $this->methode = $methode;

        return $this;
    }

    public function getControleur(): ?string
    {
        return $this->controleur;
    }

    public function setControleur(?string $controleur): static
    {
        $this->controleur = $controleur;

        return $this;
    }

    public function getDateExec(): ?\DateTimeInterface
    {
        return $this->dateExec;
    }

    public function setDateExec(?\DateTimeInterface $dateExec): static
    {
        $this->dateExec = $dateExec;

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

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(?string $log): static
    {
        $this->log = $log;

        return $this;
    }
}
