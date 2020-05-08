<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Model\SearchData;
use App\Service\MediaService;
use App\Service\ProductService;
use App\Controller\AjaxController;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class ProductController extends AbstractController
{
    
        private $productService;
       
    
        public function __construct( ProductService $productService) {
            $this->productService = $productService;
        }

    /**
     * @Route("/products", name="product_list")
     */
    public function list( Request $request, CategoryRepository $categoryRepository )
    {   
        $category=$categoryRepository->findAll();
        $filter =$request->query->all() ;
        $page = $request->query->get('page') ?? 1;

        $data = new SearchData();
      
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $products = $this->productService->getSearch($data);
        
        foreach ($products as $product) {
                $photos = $product->getPhotos();
                foreach ($photos as $photo) {
                    $photo->setPicturePath();
                }
                $priceCompare = [];
                $prices = $product->getStates();
                foreach ($prices as $price) {
                    array_push($priceCompare, $price->getPrice()) ;
                }
                $product->setLowestPrice(min($priceCompare));
            }
        return $this->render( 'product/list.html.twig', array(
            'products' => $products,
            'user' => $this->getUser(),
            'form'=>$form->createView(),
            'category'=>$category,
            

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
        $product = $this->productService->get( $id ) ;

        $photos = $product->getPhotos();
        foreach ($photos as $photo) {
            $photo->setPicturePath();
        }
        

        if($product->getSlug()) {
            $slug = $product->getSlug();
            $data = new AjaxController;

            $productdata = $data->gamedata( $slug )->getContent();
            $dataPerProduct = json_decode($productdata, true);

            $screenshots = $data->gameScreenshots($dataPerProduct["results"]['id'])->getContent();
            $screensArray = json_decode($screenshots, true);

            
            

            return $this->render( 'product/show.html.twig', array(
                'product' => $this->productService->get( $id ),
                'data' => $dataPerProduct,
                'screenshots' => $screensArray,
                'photos' => $photos,
                'user' => $this->getUser(),
            ));
        } 
        return $this->render( 'product/show.html.twig', array(
            'product' => $this->productService->get( $id ),
            'user' => $this->getUser(),
            'photos' => $photos,
        ));
    
        
    }
}


