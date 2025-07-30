<?php

namespace App\Controller\Admin;

use App\Entity\Bottles;
use App\Entity\Countries;
use App\Entity\Inventory;
use App\Entity\Regions;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/adminDashboard', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cellar');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', 'App\Entity\User');
        yield MenuItem::linkToCrud('Wines', 'fa fa-wine-bottle', Bottles::class);
        yield MenuItem::linkToCrud('Countries', 'fa fa-wine-bottle', Countries::class);
        yield MenuItem::linkToCrud('Regions', 'fa fa-wine-bottle', Regions::class);
        yield MenuItem::linkToRoute('Back to website', 'fa fa-arrow-left', 'home');
    }
}
