<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            "user" => $this->getUser()
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
}
