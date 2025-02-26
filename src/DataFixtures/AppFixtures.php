<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\ProductAttributeFactory;
use App\Factory\ProductCategoryFactory;
use App\Factory\ProductTypeFactory;
use App\Factory\ProductFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProductAttributeFactory::createMany(50);
        ProductCategoryFactory::createMany(10);
        ProductTypeFactory::createMany(10);
        ProductFactory::createMany(10);
    }
}
