<?php

namespace App\Controller;

use App\Repository\CellarsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AllCellarsController extends AbstractController
{
    #[Route('/AllCellars', name: 'allCellars')]
    public function index(CellarsRepository $CR): Response
    {
        $cellars = $CR->findAll();
        return $this->render('allCellars/allCellars.html.twig', [
            'cellars'=>$cellars,
        ]);
    }
}
