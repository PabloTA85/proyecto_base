<?php

namespace App\Controller;

use App\Entity\Pregunta;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Paginator;
use App\Repository\RespuestaRepository;

class PreguntaController extends AbstractController
{
    #[Route('/pregunta', name: 'app_pregunta')]
    public function index(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator): Response
    {
        

         // Obtener todas las preguntas activas (activa = 1)
         $query = $entityManager->getRepository(Pregunta::class)->findBy(['activa' => true]);

         
        // Configuración del paginador
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            10 
        );
    
        return $this->render('pregunta/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
//     #[Route('/pregunta', name: 'app_pregunta')]
    // public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    // {
    //     $now = new \DateTime(); // Obtener la fecha y hora actual

    //     // Asegúrate de que fInicio y fFin son de tipo DateTime y haz la comparación correctamente
    //     $query = $entityManager->getRepository(Pregunta::class)->createQueryBuilder('p')
    //         ->where('p.activa = :activa')
    //         ->andWhere('p.fInicio >= :now')
    //         ->andWhere('p.fFin <= :now')
    //         ->setParameters([
    //             'activa' => true,
    //             'now' => $now, // El parámetro 'now' debe ser un objeto DateTime
    //         ])
    //         ->getQuery();

    //     // Configuración del paginador
    //     $pagination = $paginator->paginate(
    //         $query, 
    //         $request->query->getInt('page', 1), 
    //         10
    //     );
        
    //     return $this->render('pregunta/index.html.twig', [
    //         'pagination' => $pagination,
    //     ]);
    // }
   
    #[Route('/pregunta/respondidas', name: 'app_respondidas')]
    public function respondidas(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            throw $this->createNotFoundException('El usuario no está autenticado.');
        }

        if (!$user instanceof User) {
            throw $this->createNotFoundException('El objeto no es una instancia de User.');
        }

        // Ahora puedes obtener el ID del usuario
         $usuarioId = $user->getId();

        // $usuarioId = 30;
        // Obtener las preguntas respondidas por el usuario
        $query = $entityManager->getRepository(Pregunta::class)
            ->createQueryBuilder('p')
            ->innerJoin('p.respuestas', 'r')  // Unir con las respuestas
            ->where('r.user_id = :usuarioId')  // Asegúrate de que el usuario haya respondido la pregunta
            ->setParameter('usuarioId', $usuarioId)
            ->getQuery();

        
        // Configuración del paginador
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            10
        );

        return $this->render('pregunta/respondidas.html.twig', [
            'pagination' => $pagination,
        ]);
    }



    #[Route('/pregunta/{id}', name: 'pregunta_show')]
    public function show(Pregunta $pregunta, RespuestaRepository $respuestaRepository): Response
    {
        // Obtener el conteo de respuestas por opción (A, B, C, D)
        $respuestasPorOpcion = $respuestaRepository->countRespuestasPorOpcion($pregunta->getId());

        // Preparar un arreglo para pasar a la vista
        $conteos = [
            'a' => 0,
            'b' => 0,
            'c' => 0,
            'd' => 0
        ];

        // Llenar el arreglo con los resultados
        foreach ($respuestasPorOpcion as $respuesta) {
            $opc = $respuesta['opcElegida'];
            $conteos[$opc] = $respuesta['respuestas_count'];
        }

        return $this->render('pregunta/show.html.twig', [
            'pregunta' => $pregunta,
            'conteos' => $conteos
        ]);
    }
}
