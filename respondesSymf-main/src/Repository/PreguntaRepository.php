<?php

namespace App\Repository;

use App\Entity\Pregunta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pregunta>
 */
class PreguntaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pregunta::class);
    }

    public function countRespuestasPorOpcion(Pregunta $pregunta, string $opcion): int
    {
        // Supongamos que tienes una entidad Respuesta que tiene un campo `pregunta` (relación) y `opcion` (A, B, C, D)
        return $this->createQueryBuilder('p')
            ->innerJoin('p.respuestas', 'r') // Relación con la entidad Respuesta
            ->andWhere('p.id = :preguntaId')
            ->andWhere('r.opcion = :opcion')
            ->setParameter('preguntaId', $pregunta->getId())
            ->setParameter('opcion', $opcion)
            ->select('COUNT(r.id)') // Contar las respuestas
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Método para obtener preguntas que ya han vencido
    public function findPreguntasVencidas(): array
    {
        // Obtiene todas las preguntas con fecha de fin anterior o igual al momento actual
        return $this->createQueryBuilder('p')
            ->where('p.fFin <= :now')
            ->andWhere('p.activa = :activa') // Solo seleccionamos preguntas activas
            ->setParameter('now', new \DateTime()) // Compara con la fecha actual
            ->setParameter('activa', true) // Solo seleccionamos las activas
            ->getQuery()
            ->getResult();
    }

    public function getPreguntasInactivasAhora(): array
    {
        // Obtiene todas las preguntas cuya fecha de inicio es ahora o en el pasado, y están inactivas, dentro de un rango de 1 minuto
        $now = new \DateTime();
        $interval = new \DateInterval('PT1M'); // Intervalo de 1 minuto
        $oneMinuteAgo = $now->sub($interval); // Fecha y hora de hace 1 minuto

        return $this->createQueryBuilder('p')
            ->where('p.fInicio <= :now') // Compara que la fecha de inicio sea en el pasado o ahora
            ->andWhere('p.fInicio >= :oneMinuteAgo') // Compara que la fecha de inicio sea después de hace 1 minuto
            ->andWhere('p.activa = :activa') // Solo seleccionamos las inactivas
            ->setParameter('now', $now) // Compara con la fecha y hora actual
            ->setParameter('oneMinuteAgo', $oneMinuteAgo) // Compara con la fecha y hora de hace 1 minuto
            ->setParameter('activa', false) // Solo seleccionamos las preguntas inactivas
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Pregunta[] Returns an array of Pregunta objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Pregunta
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
