<?php

namespace App\Controller;

use App\Form\WineType;
use DateTimeImmutable;
use App\Entity\Bottles;
use App\Entity\Cellars;
use App\Entity\Regions;
use App\Entity\Countries;
use App\Entity\Inventory;
use App\Form\CellarCreateType;
use App\Repository\BottlesRepository;
use App\Repository\CellarsRepository;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MyCaveController extends AbstractController
{
    #[Route('/myCellar', name: 'myCellar')]
    public function index(CellarsRepository $CR, InventoryRepository $IR, Request $request, EntityManagerInterface $entityManager, BottlesRepository $BR): Response
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
        $inventories = $IR->findBy(['cellar' => $cellar]);
        $quantities = [];
        foreach ($inventories as $inv) {
        $quantities[$inv->getWine()->getId()] = $inv->getQuantity();
    }
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
        $imageFile= $formBottle->get('imageFile')->getData();

        // Region existe? Sinon on la crée
        // $regionRepo = $entityManager->getRepository(Regions::class);
        // $region = $regionRepo->findOneBy(['name' => $regions])
        //     ?? (new Regions())->setName($regions);

        // Pareil pour le pays
        // $countryRepo = $entityManager->getRepository(Countries::class);
        // $country = $countryRepo->findOneBy(['name' => $countries])
        //     ?? (new Countries())->setName($countries);

        $entityManager->persist($regions);
        $entityManager->persist($countries);
        //pareil pour le vin:
        $bottleRepo = $entityManager->getRepository(Bottles::class);
        $bottle= $bottleRepo->findOneBy([
        'name'      => $name,
        'year'      => $year,
        'countries' => $countries,
    ]);
        // données du vin
        if (! $bottle) {
        $bottle = new Bottles();
        $bottle
            ->setName($name)
            ->setYear($year)
            ->setGrapes($formBottle->get('grapes')->getData())
            ->setRegions($regions)
            ->setCountries($countries)
            ->setDescription($description)
            ->setPublishedAt(new \DateTimeImmutable())
            ->setImageFile($imageFile)
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

            $this->addFlash('success','Cellar saved!');
            return $this->redirectToRoute('myCellar');
        }
        return $this->render('myCellar/myCellar.html.twig', [
            'cellar' => $cellar,
            'wines'=>$wines,
            'wineForm'=>$formBottle,
            'quantities'=>$quantities,
        ]);
    }

    #[Route('/stock/{wine}', name:'stock', methods:['POST'])]
public function change(
    Bottles                $wine,
    Request                $request,
    CellarsRepository      $CR,
    InventoryRepository    $IR,
    EntityManagerInterface $em
): JsonResponse 
    {

    $cellar = $CR->findOneBy(['user'=> $this->getUser()]);
    $data  = json_decode($request->getContent(), true);
    $delta = (int)($data['delta'] ?? 0);

    // find existing or new
    $inv = $IR->findOneBy(['cellar'=>$cellar, 'wine'=>$wine])
        ?? (new Inventory())
            ->setCellar($cellar)
            ->setWine($wine)
            ->setQuantity(0);

    $newQty = max(0, $inv->getQuantity() + $delta);
    $inv->setQuantity($newQty);

    $em->persist($inv);
    $em->flush();

    return $this->json(['success'=>true,'newStock'=>$newQty]);
}


    #[Route('/cellar/{cellar}', name: 'cellar',  methods: ['GET'])]
        public function userCellar(
            InventoryRepository  $IR,
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
        $inventories = $IR->findBy(['cellar' => $cellar]);
        $quantities = [];
        foreach ($inventories as $inv) {
        $quantities[$inv->getWine()->getId()] = $inv->getQuantity();
    }
        return $this->render('myCellar/userCellar.html.twig', [
            'cellar'   => $cellar,
            'wines'    => $wines,
        ]);
    }
}
