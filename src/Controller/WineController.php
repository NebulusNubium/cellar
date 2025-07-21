<?php

namespace App\Controller;

use App\Form\WineFilterType;
use App\Repository\BottlesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class WineController extends AbstractController
{
    #[Route('/wine', name: 'wine')]
    public function index(BottlesRepository $BR, Request $request): Response
    {
        $wines = $BR->findAll();
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
        ]);
    }
}
