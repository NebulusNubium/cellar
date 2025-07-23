<?php

namespace App\Form;

use App\Entity\Bottles;
use App\Entity\Regions;
use App\Entity\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

class WineFilterType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function filterWines(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Label of the Wine',
                'attr' => ['placeholder' => 'Type the label of the wine']
            ])
            ->add('year', IntegerType::class, [
                'required' => false,
                'label' => 'Vintage',
                'attr' => ['placeholder' => 'Type a year']
            ])
            ->add('regions', EntityType::class, [
                'class' => Regions::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Type its region',
                'label' => 'Regions'
            ])
            ->add('countries', EntityType::class, [
                'class' => Countries::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Type its country',
                'label' => 'country'
            ])
            ->add('grape', TextType::class, [
                'required' => false])

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
