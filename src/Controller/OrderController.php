<?php

namespace App\Controller;


use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderHasProduct;
use App\Repository\StateRepository;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

//

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function order(SessionInterface $session, ProductService $productService, StateRepository $stateRepository, Request $request, EntityManagerInterface $em, ParameterBagInterface $params, MailerInterface $mailer)
    {   
            
        $user = $this->getUser();
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            return $this->redirectToRoute('home');
        }
        if (!$user){
            return $this->redirectToRoute('home');
        }

        $address= $user -> getAddresses();
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
            $total += $item['state']->getPrice() * $item['quantity'];
        }
           
        $apkPublic='pk_test_i0owDQKVS941OjJBhfTXttY200C9cMzpZY';
        $apkSecrets='sk_test_I1XxOIaag5Z0K1pgsJKYyNnw00AzjwNhjg';
        $order = new Order();
        $form = $this->createForm( OrderType::class, $order );
        $form ->handleRequest($request);

        $loyaltyPoints = intval($user->getLoyalty());
        $loyaltyDiscout = $productService->getDiscountTotal($loyaltyPoints, $total);

        if ($form-> isSubmitted() AND $form ->isValid())
        {
            $order ->setUser($user);
            $order ->setStatus($order::PROCESSING);
            $orderNumber = "WIG-";
            $orderNumber .= $user->getId()."-";
            $orderNumber .= substr(strtoupper(md5(rand())), 0, 6);
            
            $loyalty = $form->get('loyalty')->getData();
            
            $loyaltyMinus = 0;

            if ($loyalty === true) {
                $loyaltyMinus = $loyaltyDiscout['minus'];
                $total = $loyaltyDiscout['total'];
            }

            \Stripe\Stripe::setApiKey($apkSecrets);
                $charge = \Stripe\Charge::create([
                    'amount' => $total * 100,
                    'currency' => 'eur',
                    'description' => 'Commande numéro ' .$orderNumber,
                    'source' => $order->getPaymentToken(),
                    'receipt_email' => $user->getEmail(),
                ]);
                $order -> setNumber($orderNumber);   
                

                
                //Ajouter les points de fidélité (idéalement, devrait être fait avec un tâche chrone qui irait interroger stripe pour vérifier les payements)
                
                $pointsToAdd = ceil($total / 100);
                $loyaltyPoints += $pointsToAdd - $loyaltyMinus;
                $user->setLoyalty($loyaltyPoints);
                $em->persist($order);

                foreach ($cart as $id => $quantity) {
                    $addProduct = new OrderHasProduct();
                    $addProduct->setOrderId($order);
                    $productState = $stateRepository->find($id);
                    $stock = $productState->getStock();
                    $stock -= $quantity;
                    $productState->setStock($stock);
                    $em->flush($productState);
                    $addProduct -> setStateProductId($productState);
                    $addProduct -> setQuantity($quantity);
                    $order -> addOrderHasProduct($addProduct);
                    $em->persist($addProduct);
                } 

                $em->flush();
                //Send mail
                $email = (new TemplatedEmail())
                    ->from($params->get('mail'))
                    ->to($user->getEmail())
                    ->subject('Votre commande '.$orderNumber. ' chez Winter Is Gaming')
                    ->htmlTemplate('email/emailorder.html.twig')
                    ->context(array(
                        'cart' => $cartWithData,
                        'order' => $orderNumber,
                        'user' => $user,
                        'total' => $total,
                        'loyalty' => $loyalty,
                        'loyaltyAdd'=> $pointsToAdd,
                        'loyaltyMinus' => $loyaltyMinus,
                        'loyaltyPoints' => $loyaltyPoints
                    ))
    
                ;
                $mailer->send($email);

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
            'total'=> $total,
            'stripe_public_key'=> $apkPublic,
            'loyaltyprice' => $loyaltyDiscout["total"]
        ]);
    }
}