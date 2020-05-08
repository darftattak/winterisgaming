<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaginationService{
    private $config;

    public function __construct(ParameterBagInterface $params) {
        $this->config = $params->get("pagination");
    }

    public function getPaginatedResults( array $array, $page) {
        $itemPerPage = $this->config['itemPerPage'];
        $offset = $itemPerPage * ($page -1);

        if(!empty($array)){
            $length = count($array);
        } else {
            $length = 1;
        }

        $results = array_slice($array, $offset, $itemPerPage);

        return array(
            "results" => $results,
            "maxPage" => ceil($length / $itemPerPage),
            "page" => $page,
        );
    }
}