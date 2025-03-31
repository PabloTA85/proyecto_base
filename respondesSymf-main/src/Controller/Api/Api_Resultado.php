<?php



namespace App\Controller\Api;

use App\Entity\Pregunta;
use App\Entity\User;
use App\Form\RespuestaType;
use App\Repository\RespuestaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Respuesta;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class Api_Resultado extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private RespuestaRepository $respuestaRepository;
    private SerializerInterface $serializer;

    // Constructor donde inyectamos las dependencias
    public function __construct(
        EntityManagerInterface $entityManager,
        RespuestaRepository $respuestaRepository,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->respuestaRepository = $respuestaRepository;
        $this->serializer = $serializer;
    }

    #[Route('/api/resultados/{preguntaId}', name: 'api_resultados')]
    public function obtenerResultados(int $preguntaId)
    {
        

       
    // Ejecutamos la consulta que cuenta las respuestas por opción
    $resultados = $this->respuestaRepository->countRespuestasPorOpcion($preguntaId);
    // Inicializamos el array de conteos con las opciones 'a', 'b', 'c', 'd'
    $conteos = [
        'a' => 0,
        'b' => 0,
        'c' => 0,
        'd' => 0
    ];

    // Llenamos el array de conteos con los resultados obtenidos
    foreach ($resultados as $resultado) {
        $opc = $resultado['opcElegida']; // Opción ('a', 'b', 'c', 'd')
        if (isset($conteos[$opc])) {
            $conteos[$opc] = (int) $resultado['respuestas_count']; // Contamos las respuestas por opción
        }
    }

    // Devolvemos la respuesta como JSON
    return new JsonResponse($conteos);


    }

    #[Route('/api/new-respuesta', name: 'api_new_respuesta', methods: ['POST'])]
    public function newRespuesta( EntityManagerInterface $em, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // Convertir los valores a int
        $userId = (int) $data['user_id'];  // Asegurarse de que sea un entero
        $preguntaId = (int) $data['pregunta_id'];
        $opcElegida = $data['opcElegida'];

       
        
        // Suponiendo que recibes los datos como parámetros o los creas manualmente
        $usuarioId = $userId;  // Aquí el ID del usuario
        $preguntaId = $preguntaId; // Aquí el ID de la pregunta
        $opcElegida = $opcElegida; // El contenido de la respuesta

        // Obtener el objeto User y Pregunta desde la base de datos usando el EntityManager
        $usuario = $em->getRepository(User::class)->find($usuarioId);
        $pregunta = $em->getRepository(Pregunta::class)->find($preguntaId);

        if (!$usuario || !$pregunta) {
            // Maneja el error si no se encuentra el usuario o la pregunta
            throw $this->createNotFoundException('Usuario o pregunta no encontrada');
        }

        // Crear una nueva respuesta
        $respuesta = new Respuesta();
        $respuesta->setUserId($usuario);
        $respuesta->setPreguntaId($pregunta);
        $respuesta->setOpcElegida($opcElegida);
        $respuesta->setFechaRespuesta(new \DateTime());

        // Persistir la respuesta en la base de datos
        $em->persist($respuesta);
        $em->flush();

        // Redirigir o mostrar un mensaje
         return new JsonResponse(['respuesta' => 'Respuesta creada correctamente']);
    }



    #[Route('/api/ha-respondido', name: 'api_ha_respondido', methods: ['POST'])]
    public function haRespondido(EntityManagerInterface $em,Request $request)
    {
        // Decodificar el cuerpo de la solicitud
        $data = json_decode($request->getContent(), true);

        

        // Obtener los parámetros del body
        $usuarioId = (int) $data['user_id'];  // Asegúrate de que sea un entero
        $preguntaId = (int) $data['pregunta_id'];

        // Verificar si el usuario ha respondido a la pregunta
        $haRespondido = $this->entityManager->getRepository(Respuesta::class)->haRespondidoPregunta($usuarioId, $preguntaId);

        // Devolver el resultado como JSON
        return new JsonResponse(['haRespondido' => $haRespondido]);
    }









}

            
        

        
    



    




   

