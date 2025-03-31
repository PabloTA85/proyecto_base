<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Obtener el error de autenticación si lo hay
        $error = $authenticationUtils->getLastAuthenticationError();

        // Obtener el último nombre de usuario ingresado por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        // Si el usuario está autenticado
        if ($this->getUser()) {
            // Redirigir al dashboard de EasyAdmin si es administrador
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('admin'); // Asegúrate de que esta ruta esté definida en tu configuración
            }

            // Si no es admin, redirigir a su propia página
            return $this->redirectToRoute('app_pregunta'); // Ruta personalizada para usuarios no administradores
        }

        // Si el usuario no está autenticado, renderizar la vista de login
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Este método será interceptado por el firewall de Symfony
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
