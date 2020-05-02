<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\MediaService;
use App\Service\ProductService;
use App\Controller\AjaxController;
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
            foreach ($products as $product) {
                $photos = $product->getPhotos();
                foreach ($photos as $photo) {
                    $photo->setPicturePath();
                }
            }
            $pagination = array( 'page' => 1, 'maxPage' => 1 );
        }else{
            /* Afficche tout les produits et pagination */
            $pagination = $this->productService->getPaginate( $page );
            $products = $pagination['results'];
            foreach ($products as $product) {
                $photos = $product->getPhotos();
                foreach ($photos as $photo) {
                    $photo->setPicturePath();
                }
            }
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
    public function show( Request $request, $id )
    {
        $game = $this->productService->get( $id ) ;

        $photos = $game->getPhotos();
        foreach ($photos as $photo) {
            $photo->setPicturePath();
        }
        

        if($game->getSlug()) {
            $slug = $game->getSlug();
            $data = new AjaxController;

            $gamedata = $data->gamedata( $slug )->getContent();
            $dataPerGame = json_decode($gamedata, true);

            $screenshots = $data->gameScreenshots($dataPerGame["results"]['id'])->getContent();
            $screensArray = json_decode($screenshots, true);

            
            

            return $this->render( 'product/show.html.twig', array(
                'product' => $this->productService->get( $id ),
                'data' => $dataPerGame,
                'screenshots' => $screensArray,
                'photos' => $photos,
            ));
        } 
        return $this->render( 'product/show.html.twig', array(
            'product' => $this->productService->get( $id ),
        ));
    
        
    }
}


