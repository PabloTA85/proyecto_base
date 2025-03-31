<?php


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{     // Depurar el token recibido
    

    #[Route('/confirm/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, EntityManagerInterface $entityManager): Response
    {   
        dump('Token recibido: ' . $token);
        // Buscar al usuario por el token de confirmación
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if ($user) {
            // Verificar al usuario y borrar el token
            $user->setVerified(true);
            $user->setConfirmationToken(null);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        // Si no se encuentra el usuario, mostrar un error
        return $this->render('registration/confirmation_error.html.twig');
    }
}
