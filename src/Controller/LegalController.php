<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    #[Route('/legal', name: 'legal')]
    public function index(): Response
    {
        return $this->render('legal/index.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }

        #[Route('/terms', name: 'terms')]
    public function terms(): Response
    {
        return $this->render('legal/tou.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }

}
