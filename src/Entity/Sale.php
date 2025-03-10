<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read"])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read", "write"])]
    private $client;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read", "write"])]
    private $dateVente;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(["read", "write"])]
    private $numeroVente;

    #[ORM\Column(type: 'float')]
    #[Groups(["read", "write"])]
    private $prixProduitsHT;

    #[ORM\Column(type: 'float')]
    #[Groups(["read", "write"])]
    private $prixProduitsTTC;

    #[ORM\OneToMany(mappedBy: 'sale', targetEntity: ProductSale::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[MaxDepth(2)]
    #[Groups(["read", "write"])]
    private $productSales;

    function __construct() {
        $this->productSales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTimeInterface $dateVente): self
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getNumeroVente(): ?string
    {
        return $this->numeroVente;
    }

    public function setNumeroVente(string $numeroVente): self
    {
        $this->numeroVente = $numeroVente;

        return $this;
    }

    public function getPrixProduitsHT(): ?float
    {
        return $this->prixProduitsHT;
    }

    public function setPrixProduitsHT(float $prixProduitsHT): self
    {
        $this->prixProduitsHT = $prixProduitsHT;

        return $this;
    }

    /**
     * @return Collection<int, ProductSale>
     */
    public function getProductSales(): Collection
    {
        return $this->productSales;
    }

    public function addProductSales(ProductSale $productSale): self
    {
        if (!$this->productSales->contains($productSale)) {
            $this->productSales[] = $productSale;
            $productSale->setSale($this);
        }

        return $this;
    }

    public function removerPoductSaleList(ProductSale $ProductSale): self
    {
        if ($this->productSales->removeElement($ProductSale)) {
            // set the owning side to null (unless already changed)
            if ($ProductSale->getSale() === $this) {
                $ProductSale->setSale(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of prixProduitsTTC
     */
    public function getPrixProduitsTTC()
    {
        return $this->prixProduitsTTC;
    }

    /**
     * Set the value of prixProduitsTTC
     *
     * @return  self
     */
    public function setPrixProduitsTTC($prixProduitsTTC)
    {
        $this->prixProduitsTTC = $prixProduitsTTC;

        return $this;
    }

    /**
     * Get the value of client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @return  self
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }
}