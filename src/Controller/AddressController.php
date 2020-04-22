<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    /**
     * @Route("/new-address", name="new_address")
     */
    public function new( Request $request, EntityManagerInterface $em )
    {
        $address = new Address();
        $form = $this->createForm( AddressType::class, $address );

        $form->handleRequest( $request);

        if( $form ->isSubmitted() AND $form->isValid() ) {

        }

        return $this->render('address/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => true,
        ]);
    }
}
