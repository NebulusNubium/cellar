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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Label of the Wine',
                'attr' => ['placeholder' => 'Type the label of the wine']
            ])
            ->add('year', IntegerType::class, [
                'required' => true,
                'label' => 'Vintage',
                'attr' => ['placeholder' => 'Type a year']
            ])
            ->add('region', EntityType::class, [
                'class' => Regions::class,
                'choice_label' => 'name',
                'required' => true,
                'placeholder' => 'Type its region',
                'label' => 'Regions'
            ])
            ->add('country', EntityType::class, [
                'class' => Countries::class,
                'choice_label' => 'name',
                'required' => true,
                'placeholder' => 'Type its country',
                'label' => 'country'
            ])
            ->add('grape')

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
