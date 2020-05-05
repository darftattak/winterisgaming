<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;
use App\Form\OrderType;
use Stripe\PaymentIntent;
use App\Entity\OrderHasProduct;
use App\Repository\StateRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
<<<<<<< HEAD
use Symfony\Flex\Path;
=======
>>>>>>> productInterface

//

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function order(SessionInterface $session, ProductRepository $productRepository, StateRepository $stateRepository, Request $request, EntityManagerInterface $em)
    {   
            
        $user = $this->getUser();
        $address= $user -> getAddresses();
        $itemState = [];
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'state' => $stateRepository->find($id),
                'quantity' => $quantity,
                'product' => $stateRepository->find($id)->getProduct(),
            ];

        }

        $total = 0;

        foreach($cartWithData as $item) {
            $itemProduct = $item['state']->getProduct();
            $total += $item['state']->getPrice() * $item['quantity'];
        }
<<<<<<< HEAD
           
        $apkPublic='pk_test_i0owDQKVS941OjJBhfTXttY200C9cMzpZY';
        $apkSecrets='sk_test_I1XxOIaag5Z0K1pgsJKYyNnw00AzjwNhjg';
=======

        $paymentIntent = new PaymentIntent();
        $payment = $paymentIntent->create(array(
            'amount' => $total,
            'currency' => "eur",
            'payment_method_types' => ['card']
        ));
        $paymentSecret = $payment->client_secret;  
        
>>>>>>> productInterface
        $order = new Order();
        $form = $this->createForm( OrderType::class, $order );
        $form ->handleRequest($request);
        if ($form-> isSubmitted()
        AND $form ->isValid())
        {
            $order ->setUser($user);
            $order ->setStatus($order::PROCESSING);
            $orderNumber = "WIG-";
            $orderNumber .= $user->getId()."-";
            $orderNumber .= substr(strtoupper(md5(rand())), 0, 6);
            $order -> setNumber($orderNumber);
            foreach ($cart as $id => $quantity) {
                $addProduct = new OrderHasProduct();
                $productState = $stateRepository -> find($id);
                $addProduct -> setStateProductId($productState);
                $addProduct -> setQuantity($quantity);
                $order -> addOrderHasProduct($addProduct);
                $em -> persist($addProduct);
                
            } 
            \Stripe\Stripe::setApiKey($apkSecrets);
            $charge = \Stripe\Charge::create([
                'amount' => $total * 100,
                'currency' => 'eur',
                'description' => 'Commande numéro ' .$orderNumber,
                'source' => $order->getPaymentToken(),
                'receipt_email' => $user->getEmail(),
              ]);   
        $em->persist($order);
        $em->flush();
    // vider le panier  une fois validé
    $session ->set('cart',array());
    
        //ajouter la confirmation avec un flash
    $this->addFlash( 'success', "Votre paiement à bien été pris en compte" );
    
    
    // et rediriger  vers la page d'accueil.
    return $this->redirectToRoute('home');
     
        }
       
            
        

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'cart'=>$cart,
            'cartWithData'=>$cartWithData,
            'user' =>$user, 
            'address'=>$address,
            'form' => $form->createView(),
<<<<<<< HEAD
            'total'=> $total,
            'stripe_public_key'=> $apkPublic,
            
=======
            'total'=> $total 
>>>>>>> productInterface
        ]);
    }
}