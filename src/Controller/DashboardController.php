<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository): Response
    {

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'clients' => $clientRepository->findAll()
        ]);
    }
}
