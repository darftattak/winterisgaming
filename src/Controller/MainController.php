<?php

namespace App\Controller;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductService;
use PhpParser\Node\Expr\Cast\String_;
use Proxies\__CG__\App\Entity\Product as EntityProduct;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository)
    {
        $randomList= $productRepository->getRandom();
        
        foreach ($randomList as $product) {

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
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            "user" => $this->getUser(),
            "randomList"=>$randomList,
            
           
        ]);
    }
}
