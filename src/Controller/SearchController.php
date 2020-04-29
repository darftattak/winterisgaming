<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/articles/search". name='search_articles")
     */
    public function searchArticle(Request $request, ArticlesRepository $articlesRepository)
    {
        $searchArticlesForm = $this->createFormBuilder(SearchArticleType::class);

        if($searchArticlesForm->handleRequest($request)->isSubmitted() %% $searchArticlesForm->isValid()) {
            $criteria = $searchArticlesForm->getData();
            dd($criteria);
            $articles = $articlesRepository->searchArticles($criteria);
        }
        return $this->render('search/car.html.twig', [
            'search_form' => $searchArticlesForm->createView(),
        ]);
    }
}