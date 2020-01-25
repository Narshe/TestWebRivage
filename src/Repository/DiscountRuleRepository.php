<?php

namespace App\Repository;

use App\Entity\DiscountRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DiscountRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountRule[]    findAll()
 * @method DiscountRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountRule::class);
    }

    
}
