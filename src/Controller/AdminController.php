<?php

namespace App\Controller;

use App\Repository\ClockingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ClockingRepository $repo): Response
    {
        return $this->render('admin/index.html.twig', [
            'clockings' => $repo->findForToday(),
        ]);
    }
}
