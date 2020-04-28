<?php
namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;


class AdminService {

    

    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function countWaitingOrders() {
        return $this->orderRepository->countByStatus(Order::PROCESSING);
    }
}