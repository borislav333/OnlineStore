<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductsController extends Controller
{
    /**
     * @Route("/addProduct", name="products")
     */
    public function index()
    {


        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}
