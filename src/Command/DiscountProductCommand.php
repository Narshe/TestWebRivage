<?php
namespace App\Command;

use App\Entity\DiscountRule;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Message\EmailSummary;

class DiscountProductCommand extends Command
{

    private $doctrine;
    private $bus;
    private $updated_product;

    protected static $defaultName = 'app:update_product';

    public function __construct(ManagerRegistry $doctrine, MessageBusInterface $bus)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->bus = $bus;
        $this->updated_product = new ArrayCollection();

    }

    protected function configure()
    {
        $this
            ->setDescription('Apply discount percent for the products according to some rules.')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Admin email adress', 'admin@example.com')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $adminEmail = $input->getOption('email');

        $products = new ArrayCollection($this->doctrine->getRepository(Product::class)->findAll());
        $discountRules = $this->doctrine->getRepository(DiscountRule::class)->findAll();
        
        if (empty($products) || empty($discountRules)) {
            $output->writeln("Aucun produit ou aucune expression n'a été trouvé, veuillez contacter l'administrateur pour plus d'information");
            return 0;
        }

        $em = $this->doctrine->getManager();
        $exprLanguage = new ExpressionLanguage();

        foreach ($discountRules as $discountRule) {

            $filteredProducts = $products->filter(function(Product $product) use ($exprLanguage, $discountRule){
                return $exprLanguage->evaluate($discountRule->getRuleExpression(), ['product' => $product]);
            });

            foreach($filteredProducts as $p) {

                $calc = $this->percentCalc($p->getPrice(), $discountRule->getDiscountPercent());
                                    
                if($this->updated_product->contains($p)) {

                    $p = $this->updated_product->get($this->updated_product->indexOf($p));

                    if($p->getDiscountedPrice() > $calc) {
                        $p->setDiscountedPrice($calc);
                    }
                    continue;
                }

                $p->setDiscountedPrice($calc);
                $em->persist($p);
                $this->updated_product->add($p);
            }
        }
        
        $em->flush();
        $this->bus->dispatch(new EmailSummary($this->updated_product->toArray(), $adminEmail));
        $output->writeln("Les produits ont bien été mis à jour, un récapitulatif a été envoyé à {$adminEmail}");

        return 0;
    }

    /**
     * @param float price
     * @param float percent
     * @return float
     */
    private function percentCalc(float $price, float $percent): float
    {
        return $price * (1 - ($percent/100));
    }

}