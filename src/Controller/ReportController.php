<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        // Группируем товары по кодам и типам
        $groupedData = [];
        foreach ($products as $product) {
            $code = $product->getCode();
            $type = $product->getType();
            $revenue = $product->getPrice();
            if (!isset($groupedData[$code])) {
                $groupedData[$code] = [];
            }
            if (!isset($groupedData[$code][$type])) {
                $groupedData[$code][$type] = [
                    'totalProducts' => 0,
                    'totalRevenue' => 0,
                ];
            }
            $groupedData[$code][$type]['totalProducts']++;
            $groupedData[$code][$type]['totalRevenue'] += $revenue;
        }

        return $this->render('report/index.html.twig', [
            'groupedData' => $groupedData,
        ]);
    }
}

