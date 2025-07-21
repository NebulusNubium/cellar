<?php

namespace App\Form;

use App\Entity\Bottles;
use App\Entity\Cellars;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CellarCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('bottle', EntityType::class, [
            //     'class' => Bottles::class,
            //     'choice_label' => 'name',
            // ])
            ->add('name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cellars::class,
        ]);
    }
}
