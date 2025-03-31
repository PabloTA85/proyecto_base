<?php

// src/Controller/CarritoController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'carrito')]
    public function index(Request $request): Response
    {
        // Simulación de datos en el carrito
        $carrito = [
            [
                'id' => 1,
                'nombre' => 'Producto 1',
                'precio' => 20,
                'cantidad' => 1
            ],
            [
                'id' => 2,
                'nombre' => 'Producto 2',
                'precio' => 15,
                'cantidad' => 2
            ]
        ];

        // Calcular el total
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        // Pasar la variable carrito y total a la plantilla
        return $this->render('cart/cart.html.twig', [
            'carrito' => $carrito,
            'total' => $total
        ]);
    }
}

