<?php

namespace App\Factory;

use App\Entity\Sale;
use App\Entity\Product;
use App\Entity\ProductSale;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends PersistentProxyObjectFactory<Sale>
 */
final class SaleFactory extends PersistentProxyObjectFactory
{
    private EntityManagerInterface $entityManager;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function class(): string
    {
        return Sale::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $ht = self::faker()->randomFloat(2, 10, 1000);
        $ttc = $ht * 1.2;
        return [
            'client' => ClientFactory::new(),
            'dateVente' => self::faker()->dateTime(),
            'numeroVente' => self::faker()->numberBetween(1000, 1000000),
            'prixProduitsHT' => $ht,
            'prixProduitsTTC' => $ttc
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Sale $sale) {
                $nbLignes = self::faker()->numberBetween(1, 20);

                $productRepository = $this->entityManager->getRepository(Product::class);

                for ($i = 0; $i < $nbLignes; $i++) {

                    $ht = self::faker()->randomFloat(2, 10, 1000);
                    $productSale = new ProductSale($sale);
                    $productSale->setPrixHT($ht);
                    $productSale->setQuantity(1);
                    $productSale->setProduct($productRepository->getRandomProduct());
                    $productSale->setSale($sale);

                    //$productSale = ProductSaleFactory::createOne(['sale' => $sale]);
                    $sale->addProductSales($productSale);
                }
            });
    }
}
