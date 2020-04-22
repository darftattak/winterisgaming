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
     * @Route("/address/new", name="new_address")
     */
    public function new( Request $request, EntityManagerInterface $em )
    {
        $address = new Address();
        $form = $this->createForm( AddressType::class, $address );

        $form->handleRequest( $request);

        if( $form ->isSubmitted() AND $form->isValid() ) {
            $address->setUser($this->getUser());

            $em->persist( $address );
            $em->flush();
        }

        return $this->render('address/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => true,
        ]);
    }

    /**
     * @Route("/address/{id}/update", name="update_address", requirements={"id"="\d+"})
     */
    public function update( Address $address, Request $request, EntityManagerInterface $em )  {
        if( $this->getUser() !== $address->getUser()) {
            //Return to main menu
        }

        $form = $this->createForm( AddressType::class, $address );

        $form->handleRequest( $request);
        if( $form ->isSubmitted() AND $form->isValid() ) {

        }

        return $this->render('address/form.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
        ));
    }

    /**
     * @Route("/address/{id}/remove", name="update_address", requirements={"id"="\d+"})
     */
    public function remove( Address $address, EntityManagerInterface $em )  {
        if( $this->getUser() !== $address->getUser()) {
            //Return to main menu
        }

        $em->remove( $address );
        $em->flush();

        //Make the return
    }
}
