<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Получаем все продукты
        $products = $entityManager->getRepository(Product::class)->findAll();

        // Сортировка
        $sortField = $request->query->get('sort_field', 'createdAt');
        $sortOrder = $request->query->get('sort_order', 'DESC');
        usort($products, function($a, $b) use ($sortField, $sortOrder) {
            if ($sortOrder === 'DESC') {
                return $b->{'get'.ucfirst($sortField)}() <=> $a->{'get'.ucfirst($sortField)}();
            } else {
                return $a->{'get'.ucfirst($sortField)}() <=> $b->{'get'.ucfirst($sortField)}();
            }
        });

        // Если сортировка не указана, установим сортировку по умолчанию
        if (!$request->query->has('sort_field') && !$request->query->has('sort_order')) {
            $products = array_reverse($products);
            $sortField = 'createdAt';
            $sortOrder = 'DESC';
        }

        // Пагинация
        $page = $request->query->getInt('page', 1);
        $perPage = 20;
        $totalProducts = count($products);
        $pagination = array_slice($products, ($page - 1) * $perPage, $perPage);

        return $this->render('product/index.html.twig', [
            'products' => $pagination,
            'totalProducts' => $totalProducts,
            'perPage' => $perPage,
            'currentPage' => $page,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
        ]);
    }
}

