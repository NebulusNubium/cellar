<?php

namespace App\Controller;

use App\Entity\Bottles;
use App\Entity\Cellars;
use App\Form\WineFilterType;
use App\Repository\BottlesRepository;
use App\Repository\CellarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class WineController extends AbstractController
{
    #[Route('/wine', name: 'wine')]
    public function index(BottlesRepository $BR, CellarsRepository $CR, Request $request): Response
    {
        $wines = $BR->findAll();
        $cellars= $CR->findAll();
        $form = $this->createForm(WineFilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wines = $BR->filterWines($form->getData());
        } else {
            $wines;
        }
        return $this->render('wine/wine.html.twig', [
            'wines'=>$wines,
            'form'=>$form->createView(),
            'cellars'=>$cellars,
        ]);
    }

    #[Route('/api/wines', name:'api_wines', methods:['GET'])]
public function search(BottlesRepository $repo, Request $req): JsonResponse
{
    $term  = $req->query->get('search','');
    $wines = $repo->findByTerm($term);

    $data = array_map(fn($w) => [
        'id'      => $w->getId(),
        'name'    => $w->getName(),
        'year'    => $w->getYear(),
        'grapes'  => $w->getGrapes(),
        'region'  => $w->getRegions()?->getName(),
        'country' => $w->getCountries()?->getName(),
        'description' => $w->getDescription(),
    ], $wines);

    return $this->json($data);
}
}
