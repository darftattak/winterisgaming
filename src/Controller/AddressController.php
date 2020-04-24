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
        $user = $this->getUser();

        if( $form ->isSubmitted() AND $form->isValid() ) {
            $address->setUser($user);

            $em->persist( $address );
            $em->flush();

            return $this->redirectToRoute( 'home' );
        }

        return $this->render('address/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => true,
        ]);
    }

    /**
     * @Route("/address/{id}/update", name="new_address")
     */
    public function update( Address $address, Request $request, EntityManagerInterface $em )  {
        if( $this->getUser() !== $address->getUser()) {
            return $this->redirectToRoute("home");
        }

        $form = $this->createForm( AddressType::class, $address );

        $form->handleRequest( $request);
        
        if( $form ->isSubmitted() AND $form->isValid() ) {
            $em->flush();
            $this->addFlash('success', "Votre adresse a bien été modifiée.");
            return $this->redirectToRoute('home');
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
