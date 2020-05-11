<?php

namespace App\Controller;


use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    /**
     * @Route("/address/new", name="address_new")
     */
    public function new( Request $request, EntityManagerInterface $em )
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->redirectToRoute( 'home' );
        } 

        $address = new Address();
        $form = $this->createForm( AddressType::class, $address );

        $form->handleRequest( $request);
        

        if( $form ->isSubmitted() AND $form->isValid() ) {
            $address->setUser($user);

            $em->persist( $address );
            $em->flush();

            return $this->redirectToRoute( 'home' );
        }

        return $this->render('address/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => true,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/address/{id}/update", name="address_update", requirements={"id"="\d+"})
     */
    public function update( Address $address, Request $request, EntityManagerInterface $em )  {
        if( $this->getUser() !== $address->getUser()) {
            return $this->redirectToRoute("home");
        }

        $form = $this->createForm( AddressType::class, $address );

        $form->handleRequest( $request );
        
        if( $form ->isSubmitted() AND $form->isValid() ) {
            $em->flush();
            $this->addFlash('success', "Votre adresse a bien été modifiée.");
            return $this->redirectToRoute('address_list');
        }

        return $this->render('address/form.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
            'user' => $this->getUser(),
        ));
    }

    /**
     * @Route("/address/{id}/remove", name="address_remove", requirements={"id"="\d+"})
     */
    public function remove( Address $address, EntityManagerInterface $em )  {
        if( $this->getUser() !== $address->getUser()) {
            return $this->redirectToRoute("home");
        }

        $em->remove( $address );
        $em->flush();

        //Make the return
        $this->addFlash('success', "Votre adresse a bien été supprimée.");
        return $this->redirectToRoute('address_list');
    }

    /**
     * @Route("/address/list", name="address_list")
     */
    public function index()
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute("home");
        }
        $address = $user->getAddresses();
        return $this->render('address/list.html.twig', [
            "user" => $this->getUser(),
            'address' =>  $address,
        ]);
    }

}
