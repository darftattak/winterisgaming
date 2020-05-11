<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FidelisationController extends AbstractController
{
    /**
     * @Route("/fidelisation", name="fidelisation")
     */
    public function index()
    {
        return $this->render('fidelisation/index.html.twig', [
            'controller_name' => 'FidelisationController',
        ]);
    }
}
