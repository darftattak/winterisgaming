<?php

namespace App\Controller;


use App\Repository\StateRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AjaxController extends AbstractController
{
    private $fraud;

    public function __construct(ParameterBagInterface $params) {
        $this->fraud = $params->get("fraud");
    }
    /**
     * @Route("/ajax/cart/quantity", name="ajax_cart_quantity")
     */
    public function cartQuantity(Request $request, SessionInterface $session, StateRepository $stateRepository){
        $quantity = $request->query->get('quantity');
        $id = $request->query->get('id');
        $cart = $session->get('cart', []);

        $max = $stateRepository->find($id)->getStock();

        if($quantity > $max) {
            $quantity = $max;
        }
        
        $cart[$id] = intval($quantity);

        $session->set('cart', $cart);

        return new JsonResponse($cart);

    }
    /**
     * @Route("/ajax/fraud", name="ajax_fraud")
     */
    public function fraud(Request $request, SessionInterface $session){
        $session->clear();

        $client = HttpClient::create();
        $url = $this->fraud;

        $response = $client->request('GET', $url);
        $content = $response->toArray();

        return new JsonResponse(array(
            'results' => $content['url']
        ));

    }
    /**
     * @Route("/ajax/gamedata", name="ajax_gamedata")
     */
    public function gamedata( string $slug )
    {
        $client = HttpClient::create();
        $url = 'https://api.rawg.io/api/games/'. $slug;
        $response = $client->request('GET', $url);
        $content = $response->toArray();

        return new JsonResponse( array(
            'status' => true,
            'url' => $url,
            'results' => $content,
        ));
    }
    public function gameScreenshots( int $id )
    {
        $client = HttpClient::create();
        $url = 'https://api.rawg.io/api/games/'. $id . "/screenshots";
        $response = $client->request('GET', $url);
        $content = $response->toArray();

        return new JsonResponse( array(
            'status' => true,
            'url' => $url,
            'results' => $content,
        ));
    }
}
