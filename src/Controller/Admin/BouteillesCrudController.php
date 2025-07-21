<?php
// src/Controller/Admin/BouteillesCrudController.php

namespace App\Controller\Admin;

use App\Entity\Bouteilles;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BouteillesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bouteilles::class;
    }

    // you can override configureFields() here to choose which columns show up
    // public function configureFields(string $pageName): iterable
    // {
    //     yield TextField::new('name');
    //     yield IntegerField::new('year');
    //     // …
    // }
}