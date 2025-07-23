<?php

namespace App\Controller;

use App\Repository\CellarsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/api/cellars', name: 'api_cellars', methods: ['GET'])]
    public function search(CellarsRepository $repo, Request $request)
    {
        $term    = $request->query->get('search','');
        $cellars = $repo->findByNameLike($term); 
        // implement findByNameLike() to match on user.username or cellar.name

        $data = array_map(fn($c) => [
            'id'       => $c->getId(),
            'name'     => $c->getName(),
            'owner'    => $c->getUser()->getUsername(),
            'count'    => count($c->getWines()),
        ], $cellars);

        return $this->json($data);
    }
}
