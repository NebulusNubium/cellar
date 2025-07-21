<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Regions;
use App\Entity\Countries;
use App\Entity\Bouteilles;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;





class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('My Cave');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Home', 'fa-solid fa-sign-out-alt', 'home');
        yield MenuItem::linkToCrud('The Wines', 'fa-solid fa-wine-bottle', Bouteilles::class);
        yield MenuItem::linkToCrud('Countries','fa-solid fa-earth-europe', Countries::class);
        yield MenuItem::linkToCrud('Regions','fa-regular fa-map', Regions::class);
        yield MenuItem::linkToCrud('Users','fa-regular fa-user', User::class);
    }
}
