<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;

class CategoriesController extends AbstractController
{
    private $categorieRepository;

    // Inyectamos el repository de categories   
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    public function index(): Response
    {
        $categorias = $this->categorieRepository->findAll();
        return $this->render('categories/categories.html.twig', [
            'categorias' => $categorias,
        ]);
    }
    // Mostramos error 404 si la categoría no existe por ejemplo
    public function show(int $id): JsonResponse
    {
        $categoria = $this->categorieRepository->find($id);

        if (!$categoria) {
            return new JsonResponse(['error' => 'Categoría no encontrada'], 404);
        }

        return $this->json($categoria);
    }
}
