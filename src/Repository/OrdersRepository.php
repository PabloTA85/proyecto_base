<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * Save an order (insert or update)
     */
    public function save(Orders $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }

    /**
     * Remove an order from the database
     */
    public function remove(Orders $order): void
    {
        $this->_em->remove($order);
        $this->_em->flush();
    }

    /**
     * Find orders by status
     *
     * @param string $status
     * @return Orders[]
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->orderBy('o.order_date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find orders by client
     *
     * @param int $clientId
     * @return Orders[]
     */
    public function findByClient(int $clientId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('o.order_date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find the most recent orders
     *
     * @param int $limit
     * @return Orders[]
     */
    public function findRecentOrders(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.order_date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // Método para traer todos los pedidos
    public function findAllOrders(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.client', 'c')  
            ->addSelect('c')  
            ->orderBy('o.order_date', 'DESC') 
            ->getQuery()
            ->getResult();
    }
}
