<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    // Crear un nuevo cliente
    public function create(Client $client): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($client);
        $entityManager->flush();
    }

    // Actualizar un cliente existente
    public function update(Client $client): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    // Eliminar un cliente
    public function delete(Client $client): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($client);
        $entityManager->flush();
    }

    // Obtener un cliente por su ID
    public function findClientById(int $id): ?Client
    {
        return $this->find($id);
    }

    // Obtener todos los clientes
    public function findAllClients(): array
    {
        return $this->findAll();
    }

    // Buscar clientes por criterios específicos (por ejemplo, por nombre o correo electrónico)
    public function findClientsByCriteria(array $criteria): array
    {
        return $this->findBy($criteria);
    }
}
