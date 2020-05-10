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

    public function getPaginate( $array, $page ){
        return $this->paginationService->getPaginatedResults( $array, $page );
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

    public function getParametersString(array $paramsArray) {
        $paramString = "?";
        foreach ($paramsArray as $query => $param) {
            if ($query == "page") {
                continue;
            } elseif($query == "categories") {
                foreach ($param as $category){
                    if ($paramString == "?"){
                        $paramString .= "categories[]=".$category;
                    }else{
                        $paramString .= "&categories[]=".$category;
                    }
                }
            } else {
                if ($paramString == "?"){
                    $paramString .= $query."=".$param;
                }else{
                    $paramString .= "&".$query."=".$param;
                }
            }
        }
        return $paramString;
    }

    public function getDiscountTotal(int $loyaltyPoints, int $total){
        $loyaltyMinus = 0;
        if ($loyaltyPoints >= 5000) {
            $total /= 2;
            $loyaltyMinus = 5000;
        } else {
            $sale = round((($total * $loyaltyPoints) / 10000), 2);
            $total -= $sale;
            $loyaltyMinus = $loyaltyPoints;
        }
        return ['total' => $total, 'minus' => $loyaltyMinus];
    }
}