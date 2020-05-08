<?php
namespace App\Service;

use App\Repository\ProductRepository;
use App\Service\PaginationService;

class ProductService{
    private $productRepository;
    private $paginationService;

    public function __construct( ProductRepository $productRepository, PaginationService $paginationService ){
        $this->productRepository = $productRepository;
        $this->paginationService = $paginationService;
    }

    public function getPaginate( $page ){
        return $this->paginationService->getPaginatedResults( $this->productRepository, $page );
    }

    public function get( $id ){
        return $this->productRepository->find( $id );
    }

    public function count(){
        return $this->productRepository->count( array() );
    }

    public function search( $query ){
        return $this->productRepository->searchByName( $query );
    }

    public function getRandom(){
        return $this->productRepository->getRandom();
    }

    public function findSearch($data){
        return $this->productRepository->findSearch($data);
    }
}