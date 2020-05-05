<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpClient\HttpClient;

class AjaxController extends AbstractController
{
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
