<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Categorie;
use App\Entity\ProductCategorie;
use App\Repository\ProductCategorieRepository;

class AdminProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;
    private CategorieRepository $categorieRepository;
    private ProductCategorieRepository $productCategorieRepository;


    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        CategorieRepository $categorieRepository,
        ProductCategorieRepository $productCategorieRepository
    ) {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->categorieRepository = $categorieRepository;
        $this->productCategorieRepository = $productCategorieRepository;
    }

    #[Route('/admin/producto', name: 'admin_product_index', methods: ['GET'])]
    public function index(): Response
    {
        $productos = $this->productRepository->findAllWithCategoryOrderedByName();
        $categorias = CategorieRepository::findAllCategorieDFD($this->entityManager);

        return $this->render('admin/product/product.html.twig', [
            'productos' => $productos,
            'categorias' => $categorias
        ]);
    }

    /**
     * @Route("/admin/productos/por-precio", name="admin_product_price", methods={"GET"})
     */
    public function productosByPrice(): Response
    {
        $productos = $this->productRepository->findAllOrderedByPrice();

        return $this->render('admin/product/product.html.twig', [
            'productos' => $productos,
        ]);
    }


    #[Route('/admin/producto/{id}', name: 'admin_product_show', methods: ['GET'])]

    public function show(int $id): Response
    {
        $producto = $this->productRepository->find($id);

        if (!$producto) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        return $this->render('admin/product/show.html.twig', [
            'producto' => $producto,
        ]);
    }


    #[Route('/admin/productos/buscar/{name}', name: 'admin_product_search', methods: ['GET'])]

    public function search(string $name): Response
    {
        $productos = $this->productRepository->findByName($name);

        return $this->render('admin/product/product.html.twig', [
            'productos' => $productos,
        ]);
    }


    #[Route('/admin/producto/nuevo', name: 'admin_product_create', methods: ['GET', 'POST'])]

    public function create(Request $request): JsonResponse
    {
        if (!$request->isMethod('POST')) {
            return new JsonResponse(['error' => 'Método no permitido'], 405);
        }

        // Obtener datos del formulario
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $stock = $request->request->get('stock');
        $categoriesIds = $request->request->all('category');
        $imageFile = $request->files->get('image');

        // Validar los datos
        if (!$name || !$description || !$price || !$stock) {
            return new JsonResponse(['error' => 'Faltan datos obligatorios'], 400);
        }

        // Subir la imagen 
        $imagePath = $imageFile ? $this->uploadImage($imageFile) : null;

        // Crear el producto
        $producto = new Product();
        $producto->setName($name);
        $producto->setDescription($description);
        $producto->setPrice($price);
        $producto->setStock($stock);
        $producto->setImage($imagePath);

        // Asociar categorías
        foreach ($categoriesIds as $categoryId) {
            $category = $this->entityManager->getRepository(Categorie::class)->find($categoryId);
            if ($category) {
                $productCategorie = new ProductCategorie();
                $productCategorie->setProduct($producto);
                $productCategorie->setCategory($category);
                $this->entityManager->persist($productCategorie);
            }
        }

        $this->entityManager->persist($producto);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Producto creado correctamente'], 201);
    }




    // #[Route('/admin/producto/{id}/editar', name: 'admin_product_update', methods: ['GET', 'PUT', 'POST'])]

    // public function update(Request $request, int $id): Response
    // {
    //     $producto = $this->productRepository->find($id);
    //     if (!$producto) {
    //         throw $this->createNotFoundException('Producto no encontrado');
    //     }

    //     // Obtener todas las categorías
    //     $categorias = $this->categorieRepository->findAllCategorieDFD($this->entityManager);

    //     // Obtener todos los productos
    //     $productos = $this->productRepository->findAllProducts();

    //     $form = $this->createForm(ProductType::class, $producto);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $imageFile = $form->get('image')->getData();
    //         if ($imageFile) {
    //             $newImageFilename = $this->uploadImage($imageFile);
    //             $producto->setImage($newImageFilename);
    //         }

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('admin_product_index');
    //     }

    //     return $this->render('admin/product/product.html.twig', [
    //         'form' => $form->createView(),
    //         'product' => $producto,
    //         'categorias' => $categorias,
    //         'productos' => $productos,
    //     ]);
    // }

    // Actualizar un producto existente.
    #[Route('/admin/producto/{id}/editar', name: 'admin_product_update', methods: ['POST', 'PUT'])]
    public function updateProduct(Request $request, int $id): Response
    {
        $producto = $this->productRepository->find($id);
        if (!$producto) {
            return new JsonResponse(['error' => 'Producto no encontrado'], 404);
        }

        // Obtener datos del formulario
        $name = $request->request->get('product_name');
        $description = $request->request->get('product_description');
        $price = $request->request->get('product_price');
        $stock = $request->request->get('product_stock');

        dump($producto->getStock());
        dump($stock);
        // Actualizar los datos del producto
        if ($name) {
            $producto->setName($name);
        }
        if ($description) {
            $producto->setDescription($description);
        }
        if ($price) {
            $producto->setPrice($price);
        }
        if ($stock) {
            $producto->setStock($stock);
        }

        $this->entityManager->flush();

        // mensaje de éxito al actualizar el producto
        $this->addFlash('success', 'Producto actualizado correctamente.');

        // Redirige a la lista de productos
        return $this->redirectToRoute('admin_product_index');
    }


    #[Route('/admin/producto/{id}/eliminar', name: 'admin_product_delete', methods: ['POST'])]

    public function delete(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        // Elimina el producto
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        // mensaje de éxito al eliminar el producto
        $this->addFlash('success', 'Producto eliminado correctamente.');

        // Redirige a la lista de productos
        return $this->redirectToRoute('admin_product_index');
    }

    private function uploadImage($imageFile): string
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = uniqid() . '.' . $imageFile->guessExtension();

        try {
            $destination = $this->getParameter('images_directory');
            $imageFile->move($destination, $newFilename);

            return 'imagenes/Product/' . $newFilename;
        } catch (\Exception $e) {
            throw new \Exception('Error al subir la imagen: ' . $e->getMessage());
        }
    }
}
