<?php

namespace App\Controller;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    
    /**
     * @Route("/RGPD", name="RGPD")
     */
    public function RGPD()
    {
        return $this->render('main/rgpd.html.twig', [
            'controller_name' => 'RGPDController',
            "user" => $this->getUser()
        ]);
    }
    
    /**
     * @Route("/CGU", name="CGU")
     */
    public function CGU()
    {
        return $this->render('main/cgu.html.twig', [
            'controller_name' => 'CGUController',
            "user" => $this->getUser()
        ]);
    }
    
    /**
     * @Route("/CGV", name="CGV")
     */
    public function CGV()
    {
        return $this->render('main/cgv.html.twig', [
            'controller_name' => 'CGUController',
            "user" => $this->getUser()
        ]);
    }
     /**
     * @Route("/fidelisation", name="fidelisation")
     */
    public function fidelisation()
    {
        return $this->render('main/fidelisation.html.twig', [
            'controller_name' => 'fidelisationController',
            "user" => $this->getUser()
        ]);
    }
}
