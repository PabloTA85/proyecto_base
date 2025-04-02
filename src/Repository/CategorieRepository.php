<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }


    //Método para buscar una categoría por su nombre
    public function findByName(string $name): ?Categorie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }


    //Método para buscar una categoría por su ID
    public function findById($id): ?Categorie
    {
        return $this->find($id);
    }

    public static function findAllCategorieDFD($entityManager): array
    {
        return $entityManager->getRepository(Categorie::class)->findAll();
    }

    // Método para obtener todas las categorías
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }






    // Método para crear una categoría nueva
    public function create(Categorie $categorie): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($categorie);
        $entityManager->flush();
    }


    // Método para actualizar una categoría 
    public function update(Categorie $categorie): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }


    // Método para eliminar una categoría
    public function delete(Categorie $categorie): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($categorie);
        $entityManager->flush();
    }
}

    //    /**
    //     * @return Categorie[] Returns an array of Categorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
