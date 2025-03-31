<?php

namespace App\Controller\Admin;

use App\Entity\Pregunta;
use App\Form\PreguntaType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PreguntaCrudController extends AbstractCrudController
{
    // Define cuál es la clase de la entidad que este CRUD manejará
    public static function getEntityFqcn(): string
    {
        return Pregunta::class;
    }

    // Configura los campos que se mostrarán en la vista de CRUD
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('enunciado', 'Enunciado'),
            TextField::new('a', 'Opción A'),
            TextField::new('b', 'Opción B'),
            TextField::new('c', 'Opción C')->setRequired(false),
            TextField::new('d', 'Opción D')->setRequired(false),
            ChoiceField::new('oCorrecta', 'Opción Correcta')
                ->setChoices([
                    'A' => 'a',
                    'B' => 'b',
                    'C' => 'c',
                    'D' => 'd',
                ])
                ->setRequired(true),
            BooleanField::new('activa', 'Activa')->setRequired(false),

            DateTimeField::new('fInicio', 'Fecha de Inicio')->setRequired(false),
            DateTimeField::new('fFin', 'Fecha de Fin')->setRequired(false),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Obtener los valores de las fechas directamente desde la entidad
        $fInicio = $entityInstance->getFInicio();
        $fFin = $entityInstance->getFFin();
        $activa = $entityInstance->getActiva();

        // Validar que no haya solapamiento de fechas
        if ($this->validarFechasSolapadas($entityManager, $fInicio, $fFin, $activa)) {
            // Si hay solapamiento, mostrar un mensaje de advertencia y no continuar
            $this->addFlash('warning', 'Las fechas de inicio y fin se solapan con otra pregunta activa.');
            // Aquí puedes retornar para evitar que se persista la entidad si lo deseas
            return;
        }

        // Si la validación pasa, continuar con la persistencia
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Obtener los valores de las fechas directamente desde la entidad
        $fInicio = $entityInstance->getFInicio();
        $fFin = $entityInstance->getFFin();
        $activa = $entityInstance->getActiva();

        // Validar que no haya solapamiento de fechas
        if ($this->validarFechasSolapadas($entityManager, $fInicio, $fFin, $activa)) {
            // Si hay solapamiento, mostrar un mensaje de advertencia y no continuar
            $this->addFlash('warning', 'Las fechas de inicio y fin se solapan con otra pregunta activa.');
            // Aquí puedes retornar para evitar que se actualice la entidad si lo deseas
            return;
        }

        // Si la validación pasa, continuar con la actualización
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function validarFechasSolapadas(EntityManagerInterface $entityManager, ?\DateTime $fInicio, ?\DateTime $fFin, bool $activa): bool
    {
        // Si la pregunta no está activa, no hace falta validar las fechas
        if (!$activa) {
            return false; // Si no está activa, no hay solapamiento
        }

        if ($fInicio && $fFin) {
            // Verificar si alguna otra pregunta activa tiene fechas solapadas
            $query = $entityManager->createQuery(
                'SELECT COUNT(p)
                FROM App\Entity\Pregunta p
                WHERE p.activa = 1
                AND (
                    (p.fInicio BETWEEN :fInicio AND :fFin)
                    OR (p.fFin BETWEEN :fInicio AND :fFin)
                    OR (:fInicio BETWEEN p.fInicio AND p.fFin)
                    OR (:fFin BETWEEN p.fInicio AND p.fFin)
                )'
            );

            $query->setParameter('fInicio', $fInicio);
            $query->setParameter('fFin', $fFin);

            // Contamos las coincidencias de fechas
            $count = $query->getSingleScalarResult();

            // Si el count es mayor que 0, hay solapamiento
            return $count > 0;
        }

        // Si alguna de las fechas no está definida, no hay solapamiento
        return false;
    }



    
}
