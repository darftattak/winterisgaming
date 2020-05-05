<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaginationService{
    private $config;

    public function __construct(ParameterBagInterface $params) {
        $this->config = $params->get("pagination");
    }

    public function getPaginatedResults( $repository, $page) {
        $itemPerPage = $this->config['itemPerPage'];
        $offset = $itemPerPage * ($page -1);
        return array(
            "results" => $repository->getByIsShowable(array(), array(), $itemPerPage, $offset),
            "maxPage" => ceil($repository->count( array() )/ $itemPerPage),
            "page" => $page,
        );
    }
}