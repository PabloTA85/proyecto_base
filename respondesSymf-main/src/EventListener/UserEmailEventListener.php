<?php
namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\EmailService;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserEmailEventListener implements EventSubscriberInterface
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    // Suscribir al evento 'prePersist' de Doctrine
    public static function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist', // Evento 'prePersist' y su manejador
        ];
    }

    // Método que maneja el evento 'prePersist'
    public function prePersist(LifecycleEventArgs $args): void
    {
        // Obtenemos la entidad directamente desde el evento usando getObject()
        $entity = $args->getObject(); 

        // Verificamos si la entidad es una instancia de 'User'
        if ($entity instanceof User) {
            // Generamos el token de confirmación usando UUID
            $confirmationToken = Uuid::v4()->toRfc4122(); 
            $entity->setConfirmationToken($confirmationToken);
            $entity->setVerified(false);

            // Generar el enlace de confirmación
            $confirmationUrl = $this->generateConfirmationUrl($confirmationToken);

            // Generar contenido HTML para el correo
            $htmlContent = $this->generateEmailHtmlContent($confirmationUrl);

            // Enviar el correo
            $this->emailService->sendEmailWithPdf($entity->getEmail(), 'Confirmación de Registro', $htmlContent);
        }
    }

    private function generateConfirmationUrl(string $confirmationToken): string
    {
        // Codifica el token para que no cause problemas en la URL
        $encodedToken = urlencode($confirmationToken);
        return sprintf('%s/confirm/%s', 'http://localhost:8000', $encodedToken);
    }

    private function generateEmailHtmlContent(string $confirmationUrl): string
    {
        return "<h1>Por favor, confirma tu correo electrónico</h1><p>Haz clic en el siguiente enlace para confirmar tu correo: <a href='$confirmationUrl'>$confirmationUrl</a></p>";
    }
}
