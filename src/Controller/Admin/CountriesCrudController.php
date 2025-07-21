<?php

namespace App\Controller\Admin;

use App\Entity\Countries;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;



class CountriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Countries::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Name of the country'),
            // IdField::new('id'),
            // TextField::new('title'),
            // TextEditorField::new('description'),
        ];
    }
    
    
}
