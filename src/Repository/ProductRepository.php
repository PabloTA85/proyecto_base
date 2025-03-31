<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Método para obtener productos por nombre
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Método para obtener productos con sus categorias ordenados por nombre ascendente
     */
    public function findAllWithCategoryOrderedByName(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id_product', 'p.name AS product_name', 'p.description AS product_description', 'c.name AS category_name', 'p.price AS product_price', 'p.image AS product_image') // Añadimos el campo 'image'
            ->join('p.categories', 'pc')
            ->join('pc.category', 'c')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Método para ordenar productos por su nombre de manera ascendente
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**  
     * Método para obtener todos los productos ordenados por precio de menor a mayor
     */
    public function findAllOrderedByPrice(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Método para obtener todos los productos
     */
    public function findAllProducts(): array
    {
        return $this->findAll();
    }

    /**
     * Método para obtener productos por su id
     */
    public function findProductById(int $id): ?Product
    {
        return $this->find($id);
    }
}
