<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(): Response
    {
        // Obtenemos todos los productos con categoría ordenados por nombre ascendente
        $productos = $this->productRepository->findAllWithCategoryOrderedByName();

        return $this->render("product/product.html.twig", [
            "productos" => $productos,
        ]);
    }

    public function productosByPrice(): Response
    {
        // Obtenemos todos los productos ordenados por precio
        $productos = $this->productRepository->findAllOrderedByPrice();

        return $this->render('product/product.html.twig', [
            'productos' => $productos,
        ]);
    }

   
    public function show(int $id): Response
    {
        // Obtenemos un producto por su id
        $producto = $this->productRepository->findProductById($id);

        if (!$producto) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        return $this->render('product/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    public function search(string $name): Response
    {
        // Buscamos productos por nombre
        $productos = $this->productRepository->findByName($name);

        return $this->render('product/product.html.twig', [
            'productos' => $productos,
        ]);
    }
}
