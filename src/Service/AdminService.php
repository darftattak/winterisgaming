<?php
namespace App\Service;

use App\Entity\Order;
use App\Entity\State;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StateRepository;


class AdminService {

    

    private $orderRepository;
    private $stateRepository;
    private $productRepository;

    public function __construct(OrderRepository $orderRepository, StateRepository $stateRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->stateRepository = $stateRepository;
        $this->productRepository = $productRepository;
    }

    public function countWaitingOrders() {
        return $this->orderRepository->countByStatus(Order::PROCESSING);
    }

    public function countLowAndNew() {
        return $this->stateRepository->countByLowStockAndNew(State::LOWSTOCK, State::STATENEW);
    }

    public function countNewUnavailable() {
        return $this->stateRepository->countByNewUnavailable(0, State::STATENEW);
    }

    public function countHasNoPhotos() {
        return $this->productRepository->countByHasNoPhoto();
    }

    public function countHasNoStates() {
        return $this->productRepository->countByHasNoState();
    }
}