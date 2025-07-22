<?php

namespace App\Controller;

use App\Form\WineType;
use DateTimeImmutable;
use App\Entity\Bottles;
use App\Entity\Cellars;
use App\Entity\Regions;
use App\Entity\Countries;
use App\Form\CellarCreateType;
use App\Repository\BottlesRepository;
use App\Repository\CellarsRepository;
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
        if ($this->getUser() == null) {
            throw $this->createAccessDeniedException('You have to log in to view this page.');
        };
        $user = $this->getUser();
        $cellar = [];
        $wines = [];
        
        // Récupère la cave de l'utilisateur
        if($CR->findOneBy(['user' => $user])){
        $cellar = $CR->findOneBy(['user' => $user]);
        $wines = $BR->findByUserCaves($this->getUser());
        }
            //ajout de bouteille
        $bottle = new Bottles();
        $formBottle = $this->createForm(WineType::class, $bottle)
                    ->handleRequest($request);
        if ($formBottle->isSubmitted() && $formBottle->isValid()) {
        $name= $formBottle->get('name')->getData();
        $year= $formBottle->get('year')->getData();
        $regions= $formBottle->get('regions')->getData();
        $countries = $formBottle->get('countries')->getData();
        $description= $formBottle->get('description')->getData();
        // Region existe? Sinon on la crée
        $regionRepo = $entityManager->getRepository(Regions::class);
        $region = $regionRepo->findOneBy(['name' => $regions])
            ?? (new Regions())->setName($regions);

        // Pareil pour le pays
        $countryRepo = $entityManager->getRepository(Countries::class);
        $country = $countryRepo->findOneBy(['name' => $countries])
            ?? (new Countries())->setName($countries);

        $entityManager->persist($region);
        $entityManager->persist($country);
        //pareil pour le vin:
        $bottleRepo = $entityManager->getRepository(Bottles::class);
        $bottle= $bottleRepo->findOneBy([
        'name'      => $name,
        'year'      => $year,
        'countries' => $country,
    ]);
        // données du vin
        if (! $bottle) {
        // 5) build a new Bottles only if none existed
        $bottle = new Bottles();
        $bottle
            ->setName($name)
            ->setYear($year)
            ->setGrapes($formBottle->get('grapes')->getData())
            ->setRegions($region)
            ->setCountries($country)
            ->setDescription($description)
            ->setPublishedAt(new \DateTimeImmutable())
        ;
        $entityManager->persist($bottle);
        $bottle->addCellar($cellar);
        $cellar->addWine($bottle)
            ->setPublishedAt(new \DateTimeImmutable());
        $entityManager->persist($cellar);
        $entityManager->flush();
        }else{
            $this->addFlash('Error','Bottle already exists!');
            return $this->redirectToRoute('myCellar');
        }
        // $entityManager->flush();
        // $cellar->addWine($bottle)
        //     ->setPublishedAt(new \DateTimeImmutable());
        // $entityManager->persist($cellar);
        // $entityManager->flush();

            $this->addFlash('success','Cellar saved!');
            return $this->redirectToRoute('myCellar');
        }
        return $this->render('myCellar/myCellar.html.twig', [
            'cellar' => $cellar,
            'wines'=>$wines,
            'wineForm'=>$formBottle,
        ]);
    }




    #[Route('/cellar/{cellar}', name: 'cellar',  methods: ['GET'])]
        public function userCellar(
            CellarsRepository  $CR,
            BottlesRepository  $BR,
            Request            $request,
            EntityManagerInterface $em,
            ?Cellars $cellar =null,
        ): Response {
        if (null === $cellar) {
        throw $this->createNotFoundException('Cellar not found.');
            }

        // on fetch les vins, sinon c'est vide
        $wines  = $cellar
            ? $BR->findByCellar($cellar)
            : [];

        return $this->render('myCellar/userCellar.html.twig', [
            'cellar'   => $cellar,
            'wines'    => $wines,
        ]);
    }

}
