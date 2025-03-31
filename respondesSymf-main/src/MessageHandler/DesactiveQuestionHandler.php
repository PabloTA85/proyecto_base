<?php

namespace App\MessageHandler;

use App\Message\DesactiveQuestion;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Repository\PreguntaRepository;
use Doctrine\ORM\EntityManagerInterface;

#[AsMessageHandler]
final class DesactiveQuestionHandler
{
    
    private EntityManagerInterface $entityManager;

    public function __construct(
        private PreguntaRepository $preguntaRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->preguntaRepository = $preguntaRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(DesactiveQuestion $message): void
    {
        $this->desactivarPreguntasVencidas();
    }

    // Función que desactiva todas las preguntas vencidas
    public function desactivarPreguntasVencidas(): void
    {
        // Obtener las preguntas vencidas
        $preguntasVencidas = $this->preguntaRepository->findPreguntasVencidas();
        if (count($preguntasVencidas) === 0) {
            echo "No hay preguntas vencidas.\n";
            return;
        }else{
            echo "Se encontraron " . count($preguntasVencidas) . " preguntas vencidas.\n";
        }
      
        foreach ($preguntasVencidas as $pregunta) {
            // Marca la pregunta como inactiva
            $pregunta->setActiva(false);
            
            // Persistir los cambios
            $this->entityManager->persist($pregunta);
            echo "Pregunta con ID {$pregunta->getId()} desactivada.\n";
        }

        // Guardar todos los cambios a la base de datos
        $this->entityManager->flush();
        echo "Proceso completado.\n";

        
    }

    
}
