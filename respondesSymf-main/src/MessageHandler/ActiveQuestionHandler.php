<?php

namespace App\MessageHandler;

use App\Message\ActiveQuestion;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Repository\PreguntaRepository;
use Doctrine\ORM\EntityManagerInterface;

#[AsMessageHandler]
final class ActiveQuestionHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        private PreguntaRepository $preguntaRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->preguntaRepository = $preguntaRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(ActiveQuestion $message): void
    {
        $this->activarPreguntas();
    }
    

    public function activarPreguntas(): void
    {
        // Obtener las preguntas inactivas que deberían ser activadas
        $preguntasInactivas = $this->preguntaRepository->getPreguntasInactivasAhora();
        if (count($preguntasInactivas) === 0) {
            echo "No hay preguntas inactivas para activar.\n";
            return;
        } else {
            echo "Se encontraron " . count($preguntasInactivas) . " preguntas inactivas para activar.\n";
        }

        foreach ($preguntasInactivas as $pregunta) {
            // Marca la pregunta como activa
            $pregunta->setActiva(true);

            // Persistir los cambios
            $this->entityManager->persist($pregunta);
            echo "Pregunta con ID {$pregunta->getId()} activada.\n";
        }

        // Guardar todos los cambios a la base de datos
        $this->entityManager->flush();
        echo "Proceso completado.\n";   
    }   
}
