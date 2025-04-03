<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'carrito')]
    public function index(SessionInterface $session): Response
    {
        // Obtener el carrito de la sesión, o inicializarlo vacío si no existe
        $carrito = $session->get('carrito', []);

        // Calcular el total
        $total = array_reduce($carrito, function ($sum, $producto) {
            return $sum + ($producto['precio'] * $producto['cantidad']);
        }, 0);

        return $this->render('cart/cart.html.twig', [
            'carrito' => $carrito,
            'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'carrito_agregar')]
    public function addToCart(int $id, Request $request, SessionInterface $session): Response
    {
        // Simulación de productos (esto normalmente vendría de la base de datos)
        $productosDisponibles = [
            1 => ['id' => 1, 'nombre' => 'Producto 1', 'precio' => 20],
            2 => ['id' => 2, 'nombre' => 'Producto 2', 'precio' => 15],
            3 => ['id' => 3, 'nombre' => 'Producto 3', 'precio' => 30],
        ];

        // Obtener el carrito de la sesión
        $carrito = $session->get('carrito', []);

        // Verificar si el producto existe en el catálogo
        if (isset($productosDisponibles[$id])) {
            if (isset($carrito[$id])) {
                // Si el producto ya está en el carrito, aumentar la cantidad
                $carrito[$id]['cantidad']++;
            } else {
                // Agregar el producto al carrito con cantidad 1
                $carrito[$id] = $productosDisponibles[$id];
                $carrito[$id]['cantidad'] = 1;
            }
        }

        // Guardar el carrito en la sesión
        $session->set('carrito', $carrito);

        return $this->redirectToRoute('carrito');
    }

    #[Route('/cart/remove/{id}', name: 'carrito_eliminar')]
    public function removeFromCart(int $id, SessionInterface $session): Response
    {
        $carrito = $session->get('carrito', []);

        // Eliminar el producto si está en el carrito
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
        }

        $session->set('carrito', $carrito);

        return $this->redirectToRoute('carrito');
    }

    #[Route('/cart/clear', name: 'carrito_vaciar')]
    public function clearCart(SessionInterface $session): Response
    {
        $session->remove('carrito');

        return $this->redirectToRoute('carrito');
    }
}
