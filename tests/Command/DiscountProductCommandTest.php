<?php

namespace App\Tests\Command;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use App\DataFixtures\DiscountRuleFixtures;
use App\DataFixtures\ProductFixtures;
use App\Entity\Product;

class DiscountProductCommand extends KernelTestCase
{
    use FixturesTrait;

    public function testExecute()
    {   
        $emailAdmin = "test@test.com";
        $this->loadFixtures([ProductFixtures::class, DiscountRuleFixtures::class]);

        $kernel = self::bootKernel();
        $doctrine = self::$container->get('doctrine');
        $repo = $doctrine->getRepository(Product::class);

        $product = $repo->findOneBy(['name' => 'Enceinte Bluetooth']);
        $this->assertNull($product->getDiscountedPrice());

        $application = new Application($kernel);

        $command = $application->find('app:update_product');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--email' => $emailAdmin,
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains("Les produits ont bien été mis à jour, un récapitulatif a été envoyé à {$emailAdmin}", $output);

        $product = $repo->findOneBy(['name' => 'Enceinte Bluetooth']);

        $this->assertEquals(80.0, $product->getDiscountedPrice());
    }
}