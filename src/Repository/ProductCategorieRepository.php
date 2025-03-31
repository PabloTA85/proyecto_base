<?php

namespace App\Repository;

use App\Entity\ProductCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategorie::class);
    }

    /**
     * Obtiene todos los productos de una categoría específica
     */
    public function findProductsByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('pc') 
            ->innerJoin('pc.product', 'p') 
            ->addSelect('p')
            ->where('pc.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }
}
