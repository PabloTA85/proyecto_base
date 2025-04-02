<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminOrderController extends AbstractController
{
    private $ordersRepository;
    private $clientRepository;
    private $entityManager;

    // Inyectamos OrdersRepository y ClientRepository en el constructor
    public function __construct(OrdersRepository $ordersRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager)
    {
        $this->ordersRepository = $ordersRepository;
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/pedidos', name: 'admin_pedidos', methods: ['GET'])]
    public function index(): Response
    {
        // Obtener todos los pedidos desde el repositorio
        $orders = $this->ordersRepository->findAllOrders();  

        // Obtener todos los clientes desde el repositorio
        $clients = $this->clientRepository->findAll();  

        // Renderizamos la vista y pasamos los pedidos y los clientes como variables
        return $this->render('admin/order/order.html.twig', [
            'orders' => $orders,  
            'clients' => $clients,  
        ]);
    }

    /**
     * Ver el detalle de una orden específica.
     *
     * @Route("/admin/order/{id}", name="order_detail")
     */
    public function orderDetail(int $id, OrdersRepository $ordersRepository): Response
    {
        // Obtener la orden por su ID
        $order = $ordersRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        return $this->render('orders/order_detail.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * Crear una nueva orden.
     *
     * @Route("/admin/orders/new", name="orders_create")
     */
    // public function createOrder(Request $request, OrdersRepository $ordersRepository): Response
    // {
    //     // Crear una nueva orden
    //     $order = new Orders();

    //     // Aquí puedes usar formularios para obtener los datos de la nueva orden
    //     if ($request->isMethod('POST')) {
    //         $order->setClient($request->get('client'))  // Asegúrate de validar los datos
    //             ->setOrderDate(new \DateTime())
    //             ->setTotal($request->get('total'))
    //             ->setStatus('Pending');  // Por ejemplo, establecer estado como 'Pending'

    //         // Guardar la orden en la base de datos
    //         $ordersRepository->save($order);

    //         return $this->redirectToRoute('admin_orders_list');
    //     }

    //     return $this->render('orders/create_order.html.twig');
    // }

    /**
     * Eliminar una orden.
     *
     * @Route("/admin/orders/delete/{id}", name="orders_delete")
     */
    public function deleteOrder(int $id, OrdersRepository $ordersRepository): Response
    {
        // Buscar la orden por su ID
        $order = $ordersRepository->find($id);

        if (!$order) {
            throw $this->createNotFoundException('Order not found');
        }

        // Eliminar la orden
        $ordersRepository->remove($order);

        // Redirigir a la lista de órdenes
        return $this->redirectToRoute('admin_orders_list');
    }

    /**
     * Actualizar el estado de una orden.
     *
     * @Route("/admin/orders/update/{id}", name="orders_update")
     */
    // public function updateOrderStatus(int $id, OrdersRepository $ordersRepository, Request $request): Response
    // {
    //     // Buscar la orden por su ID
    //     $order = $ordersRepository->find($id);

    //     if (!$order) {
    //         throw $this->createNotFoundException('Order not found');
    //     }

    //     // Actualizar el estado de la orden
    //     if ($request->isMethod('POST')) {
    //         $newStatus = $request->get('status');
    //         $order->setStatus($newStatus);

    //         // Guardar los cambios en la base de datos
    //         $ordersRepository->save($order);

    //         return $this->redirectToRoute('order_detail', ['id' => $id]);
    //     }

    //     return $this->render('orders/update_order_status.html.twig', [
    //         'order' => $order,
    //     ]);
    // }
}
