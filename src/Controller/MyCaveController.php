<?php

namespace App\Controller;

use App\Entity\Cellars;
use App\Form\CellarCreateType;
use App\Repository\BottlesRepository;
use App\Repository\CellarsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MyCaveController extends AbstractController
{
    #[Route('/myCellar', name: 'myCellar')]
    public function index(CellarsRepository $CR, Request $request, EntityManagerInterface $entityManager, BottlesRepository $BR): Response
    {
        $user = $this->getUser();
        $cellars= $CR->findAll();
        $cellar = new Cellars();
        $wines= [];
        if (!$user) {
            throw $this->createAccessDeniedException('You have to log in to view this page.');
        };
        // Récupère la cave de l'utilisateur
        if($CR->findOneBy(['user' => $user])){
        $cellar = $CR->findOneBy(['user' => $user]);
        $wines = $BR->findByUserCaves($this->getUser());
        }
        $formCreate = $this->createForm(CellarCreateType::class, $cellar);
        $formCreate->handleRequest($request);
            if ($formCreate->isSubmitted() && $formCreate->isValid()) {
            $user= $this->getUser();
            // on le lie à l'image avant le persist()
            $cellar->setUser($user);
            $cellar->setPublishedAt(new DateTimeImmutable());
            $entityManager->persist($cellar);
            $entityManager->flush();
            
            $this->addFlash('success', 'Image enregistrée !');
            return $this->redirectToRoute('myCellar');}

        
        
        return $this->render('myCellar/myCellar.html.twig', [
            'cellar' => $cellar,
            'addCellar'=> $formCreate->createView(),
            'wines'=>$wines,
            'cellars'=>$cellars,
        ]);
    }
    #[Route('/cellar/{cellar}', name: 'cellar')]
        public function userCellar(
            CellarsRepository  $CR,
            BottlesRepository  $BR,
            Request            $request,
            EntityManagerInterface $em
        ): Response {
        /** @var \App\Entity\User $user */
        $user   = $this->getUser();
        // 1) Pull the single cellar that belongs to this user (or null):
        $cellar = $CR->findOneBy(['user' => $user]);

        // 2) If they have a cellar, fetch its wines; otherwise empty array
        $wines  = $cellar
            ? $BR->findByUserCaves($user)
            : [];

        // 3) Build your “create cellar” form on either a new or existing cellar:
        if (! $cellar) {
            $cellar = new Cellars();
        }
        $form = $this->createForm(CellarCreateType::class, $cellar)
                    ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cellar->setUser($user)
                ->setPublishedAt(new \DateTimeImmutable());
            $em->persist($cellar);
            $em->flush();

            $this->addFlash('success','Cellar saved!');
            return $this->redirectToRoute('cellar');
        }

        return $this->render('myCellar/userCellar.html.twig', [
            'cellar'   => $cellar,           // single entity or new one
            'wines'    => $wines,            // array of bottles
            'cellarForm' => $form->createView(),
        ]);
    }

}
