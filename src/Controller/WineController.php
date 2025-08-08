<?php

namespace App\Controller;
use App\Form\WineType;
use App\Entity\Bottles;
use App\Entity\Cellars;
use App\Form\WineFilterType;
use App\Repository\BottlesRepository;
use App\Repository\CellarsRepository;
use App\Repository\RegionsRepository;
use App\Repository\CountriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Rating;
use App\Repository\RatingRepository;

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
public function search(
    BottlesRepository $repo,
    Request $req,
    AuthorizationCheckerInterface $auth,
    CsrfTokenManagerInterface $csrf
): JsonResponse {
    $term  = $req->query->get('search','');
    $wines = $repo->findByTerm($term);

    $data = array_map(function ($w) use ($auth, $csrf) {
        return [
            'id'          => $w->getId(),
            'name'        => $w->getName(),
            'year'        => $w->getYear(),
            'grapes'      => $w->getGrapes(),
            'regionName'  => $w->getRegions()?->getName(),
            'countryName' => $w->getCountries()?->getName(),
            'description' => $w->getDescription(),
            'imageName'   => $w->getImageName(),
            'isAdmin'     => $auth->isGranted('ROLE_ADMIN'),
            'csrfAdd'     => $csrf->getToken('add-wine' . $w->getId())->getValue(),
            'csrfDelete'  => $csrf->getToken('delete' . $w->getId())->getValue(),
        ];
    }, $wines);

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

    #[Route('/wine/{id}/delete', name: 'delete', methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
public function delete(Request $request, Bottles $wine, EntityManagerInterface $entityManager): Response
{
    $submittedToken = $request->request->get('_token');

    if ($this->isCsrfTokenValid('delete' . $wine->getId(), $submittedToken)) {
        $entityManager->remove($wine);
        $entityManager->flush();
        $this->addFlash('success', 'Wine deleted successfully!');
    } else {
        $this->addFlash('error', 'Invalid CSRF token.');
    }

    return $this->redirectToRoute('wine');
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

    #[Route('/wine/{id}/rate', name: 'rate', methods: ['POST'])]
    public function rate(Bottles $wine, Request $request, RatingRepository $ratingRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException('You must be logged in to rate a wine.');
        }

        $value = (int) $request->request->get('rating');
        $user = $this->getUser();

        $rating = $ratingRepository->findOneBy(['bottle' => $wine, 'user' => $user]);
        if (!$rating) {
            $rating = new Rating();
            $rating->setBottle($wine);
            $rating->setUser($user);
        }
        $rating->setValue($value);

        $entityManager->persist($rating);
        $entityManager->flush();

        return $this->redirectToRoute('wine');
    }

}