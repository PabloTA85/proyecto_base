<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registro', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Debug the submitted data
            // dd($request->request->all());


            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Encode/hash the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Assign ROLE_USER as a default role
            // if (empty($user->getRoles())) {
            $user->setRoles(['ROLE_USER']);
            // }

            $entityManager->persist($user);
            $entityManager->flush();

            // Add a success flash message
            // $this->addFlash('success', '¡Registro completado exitosamente!');

            // do anything else you need here, like send an email

            // Log the user in automatically
            // $userAuthenticator->authenticateUser($user, $authenticator, $request);

            // Redirect to home page (index route)
            return $this->redirectToRoute('index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
