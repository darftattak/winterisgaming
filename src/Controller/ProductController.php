<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\MediaService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    
        private $productService;
       
    
        public function __construct( ProductService $productService) {
            $this->productService = $productService;
           
        }
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/products.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/products", name="product_list")
     */
    public function list( Request $request )
    {
        $query = $request->query->get('query');
        $page = $request->query->get('page') ?? 1;

            /* Gestion de la recherche */
        if( !empty( $query ) ){
            $products = $this->productService->search( $query );
            $pagination = array( 'page' => 1, 'maxPage' => 1 );
        }else{
            /* Afficche tout les produits et pagination */
            $pagination = $this->productService->getPaginate( $page );
            $products = $pagination['results'];
        }

        return $this->render( 'product/list.html.twig', array(
            'products' => $products,
            'page' => $pagination['page'],
            'maxPage' => $pagination['maxPage'],
            
        ));
    }

    /**
     * @Route("/product/random", name="product_random")
     */
    public function random()
    {
        return $this->redirectToRoute( 'product_show', array(
            'id' => $this->productService->getRandom()
        ));
    }

    /**
     * @Route("/product/{id}", name="product_show", requirements={"id"="\d+"})
     */
    public function show( $id )
    {
        return $this->render( 'product/show.html.twig', array(
            'product' => $this->productService->get( $id ),
        ));
    }

   

  
    
}


