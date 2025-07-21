<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AllCellarsController extends AbstractController
{
    #[Route('/all/cellars', name: 'app_all_cellars')]
    public function index(): Response
    {
        return $this->render('all_cellars/index.html.twig', [
            'controller_name' => 'AllCellarsController',
        ]);
    }
}
