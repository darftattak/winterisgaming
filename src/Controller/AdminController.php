<?php

namespace App\Controller;

use App\Entity\State;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminService;

class AdminController extends AbstractController
{
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function dashboard()
    {
        $user = $this->getUser();
        return $this->render('admin/dash.html.twig', [
            'user' => $user,
            'nWaitingOrders' => $this->adminService->countWaitingOrders(),
            'nLowStockAndNew' =>$this->adminService->countLowAndNew(),
            'nUnavailable' => $this->adminService->countNewUnavailable(),
            'nNoPhoto' => $this->adminService->countHasNoPhotos(),
            'nNoState' => $this->adminService->countHasNoStates(),
            'nLowLimit' => State::LOWSTOCK,
        ]);
    }
}
