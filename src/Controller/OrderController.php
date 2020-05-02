<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


//

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function order(SessionInterface $session, ProductRepository $productRepository, Request $request, EntityManagerInterface $em)
    {   
            
        $user = $this->getUser();
        $address= $user -> getAddresses();
        $itemState = [];
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];

        }

        $total = 0;

        foreach($cartWithData as $item) {
            $itemState = $item['product']->getStates();
           foreach($itemState as $eachItem) {
               $total += $eachItem->getPrice() * $item['quantity'];
           }
        }
        
        $order = new Order();
        $form = $this->createForm( OrderType::class, $order );
        
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'cart'=>$cart,
            'cartWithData'=>$cartWithData,
            'user' =>$user, 
            'address'=>$address,
            'form' => $form->createView(),
            'total'=> $total 

        ]);
    }

    

}
