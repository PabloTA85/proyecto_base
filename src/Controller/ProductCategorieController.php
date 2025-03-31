<?php

namespace App\Controller;

use App\Repository\ProductCategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductCategorieController extends AbstractController
{
    // Con este método obtenemos los productos de una categoría a partir de su ID
    #[Route('/category/{id}/products', name: 'category_products', methods: ['GET'])]
    public function getProductsByCategory(int $id, ProductCategorieRepository $productCategorieRepository): JsonResponse
    {
        $products = $productCategorieRepository->findProductsByCategory($id);

        if (!$products) {
            return $this->json(['message' => 'No products found for this category'], 404);
        }

        return $this->json($products);
    }
}
