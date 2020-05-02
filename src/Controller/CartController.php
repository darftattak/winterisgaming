<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddItemType;
use App\Repository\ProductRepository;
use App\Repository\StateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, StateRepository $stateRepository)
    {
        $itemProduct = [];
        $cart = $session->get('cart', []);

        $cartWithData = [];

        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $stateRepository->find($id),
                'quantity' => $quantity
            ];

        }

        $total = 0;

        foreach($cartWithData as $item) {
            $itemProduct = $item['product']->getProduct();
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        if($itemProduct) {
            return $this->render('cart/index.html.twig', [
                'item' => $itemProduct,
                'total' => $total,
                'items' =>  $cartWithData,
            ]);
        }
        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'items' =>  $cartWithData,
        ]);
    }
    
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session) {

        // Récupérer le panier dans la session
        // Dans la session regarder si une donnée s'appelle 'cart'
        // Si je n'ai pas de panier alors j'ai un tableau vide
        $cart = $session->get('cart', []);

        // Condition permettant d'incrémenter un produit déjà présent au panier
        if(!empty($cart[$id]))  {
            $cart[$id]++;
        } else {

        // Ajouter le produit au panier
            $cart[$id] = 1;
        }
        
        // Remettre le panier dans la session pour sauvegarder les données
        $session->set('cart', $cart);

        /* dd($session->get('cart')); */
        return $this->redirectToRoute("cart");
    }

     /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session) {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart");
    }

}
