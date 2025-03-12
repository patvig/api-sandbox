<?php
// src/Entity/ProductSale.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Repository\ProductSaleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductSaleRepository::class)]
#[ApiResource(security: "is_granted('ROLE_USER')")]
class ProductSale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Sale::class, inversedBy: 'productSales')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(readableLink: false, writableLink: false)] 
    private ?Sale $sale = null;

    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read", "write"])]
    private $produit;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read", "write"])]
    private $quantite;

    #[ORM\Column(type: 'float')]
    #[Groups(["read", "write"])]
    private $prixHT;

    #[Groups(['read'])]
    #[SerializedName("productName")]
    public function getProductName(): ?string
    {
        return $this->getProduct()->getName();
    }

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    #[Groups(["read"])]
    public function getNom(): ?string
    {
        return $this->getProduct()->getName();
    }

    #[Groups(["read"])]
    public function getIdProduit(): ?string
    {
        return $this->getProduct()->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->produit;
    }

    public function setProduct(Product $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantite;
    }

    public function setQuantity($quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixHT()
    {
        return $this->prixHT;
    }

    public function setPrixHT($prixHT): self
    {
        $this->prixHT = $prixHT;

        return $this;
    }
}