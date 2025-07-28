<?php

namespace App\Controller;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\WineType;
use App\Entity\Bottles;
use App\Entity\Cellars;
use App\Form\WineFilterType;
use App\Repository\BottlesRepository;
use App\Repository\CellarsRepository;
use App\Repository\CountriesRepository;
use App\Repository\RegionsRepository;
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


    #[Route('/wine/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(CountriesRepository $CR, RegionsRepository $RR, Bottles $wine, Request $request, EntityManagerInterface $entityManager, BottlesRepository $BR): Response
    {
        $formBottle = $this->createForm(WineType::class, $wine)
                    ->handleRequest($request);
        if ($formBottle->isSubmitted() && $formBottle->isValid()) {
        $entityManager->persist($wine);
        $entityManager->flush();
        return $this->redirectToRoute('wine'); 
        }
        $wines = $BR->findAll();
        $this->addFlash('success','Wine updated!');
        return $this->render('wine/edit.html.twig', [
            'wine'=>$wine,
            'wineForm'=>$formBottle,
            'wines'=>$wines,
        ]);
    }

     #[Route('/wine/{id}/add/', name: 'add', methods: ['GET', 'POST'])]
    public function add(Bottles $wine, Request $request, EntityManagerInterface $entityManager, CellarsRepository $CR): Response
    {
        if ($this->getUser() == null) {
            throw $this->createAccessDeniedException('You have to log in to view this page.');
        };
        $user = $this->getUser();
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('add-wine'.$wine->getId(), $token)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }
        $cellar = $CR->findOneBy(['user' => $user]);

        $cellar->addWine($wine)
            ->setPublishedAt(new \DateTimeImmutable());
        $entityManager->persist($cellar);
        $entityManager->flush();
        return $this->redirectToRoute('wine'); 
        $this->addFlash('success','Wine added!');
    }

}