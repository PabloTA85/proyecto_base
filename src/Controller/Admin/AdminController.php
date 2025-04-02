<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    // Ruta para el panel de administración
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig'); // Ajusta según la vista que quieras cargar
    }

    // Ruta para el login de admin
    #[Route('/admin/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si ya está autenticado, redirige al panel de administración
        if ($this->getUser()) {
            return $this->redirectToRoute('admin'); 
        }

        // Obtén el error de autenticación si lo hay
        $error = $authenticationUtils->getLastAuthenticationError();
        // Obtén el último nombre de usuario intentado
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
