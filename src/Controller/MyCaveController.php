<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MyCaveController extends AbstractController
{
    #[Route('/my/cave', name: 'app_my_cave')]
    public function index(): Response
    {
        return $this->render('my_cave/index.html.twig', [
            'controller_name' => 'MyCaveController',
        ]);
    }
}
