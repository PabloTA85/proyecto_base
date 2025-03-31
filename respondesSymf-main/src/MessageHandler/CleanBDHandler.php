<?php

namespace App\MessageHandler;

use App\Message\CleanBD;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;


#[AsMessageHandler]
final class CleanBDHandler
{
    

    // Inyectamos tanto el UserRepository como el EntityManager
    public function __construct(
        private UserRepository $userRepository,
       private EntityManagerInterface $entityManager,
       private LoggerInterface $logger
    ) 
    {
        
    }

    public function __invoke(CleanBD $message): void
    {
        $this->deleteUnverifiedUsers();
        
    }

    private function deleteUnverifiedUsers(): void
    {
        $logger = $this->logger;
        $users = $this->userRepository->findBy(['isVerified' => false]);

        if (count($users) === 0) {
            // echo "No hay usuarios no verificados.\n";
            
            $logger->info('No hay usuarios no verificados.');
            return;
        }

       
        foreach ($users as $user) {
            $this->entityManager->remove($user);  
            // echo "Usuario con ID {$user->getId()} eliminado.\n";
            $logger->info('Usuario con ID {$user->getId()} eliminado.');
        }

        
        $this->entityManager->flush();

        // echo "Proceso completado.\n";
        $logger->info('Proceso completado.');
    }
}
