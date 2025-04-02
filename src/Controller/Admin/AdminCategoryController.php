<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;

class AdminCategoryController extends AbstractController
{
    private $categorieRepository;
    private $entityManager;

    public function __construct(CategorieRepository $categorieRepository, EntityManagerInterface $entityManager)
    {
        $this->categorieRepository = $categorieRepository;
        $this->entityManager = $entityManager;
    }

    
    // Se muestran todas las categorías.
    #[Route('/admin/categorias', name: 'admin_categorias', methods: ['GET'])]
    public function index(): Response
    {
        $categorias = $this->categorieRepository->findAll();
        return $this->render('admin/category/category.html.twig', [
            'categorias' => $categorias,
        ]);
    }


    // Crear una categoría nueva.
    #[Route('/admin/categoria/nueva', name: 'admin_categoria_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'])) {
            return new JsonResponse(['error' => 'El nombre es obligatorio'], 400);
        }

        

        $categoria = new Categorie();
        $categoria->setName($data['name']);

        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Categoría creada con éxito'], 201);
    }


    // Actualizar una categoría existente. 
    #[Route('/admin/categoria/{id}', name: 'admin_categoria_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $categoria = $this->categorieRepository->find($id);
        if (!$categoria) {
            return new JsonResponse(['error' => 'Categoría no encontrada'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) {
            $categoria->setName($data['name']);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Categoría actualizada con éxito']);
    }

    
    // Eliminar una categoría existente. 
    #[Route('/admin/categoria/{id}', name: 'admin_categoria_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $categoria = $this->categorieRepository->find($id);
        if (!$categoria) {
            return new JsonResponse(['error' => 'Categoría no encontrada'], 404);
        }

        $this->entityManager->remove($categoria);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Categoría eliminada con éxito']);
    }
}
