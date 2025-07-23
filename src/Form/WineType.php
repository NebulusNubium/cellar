<?php

namespace App\Form;

use App\Entity\Bottles;
use App\Entity\Regions;
use App\Entity\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class WineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('year')
            ->add('grapes')
            ->add('description')
            ->add('regions', EntityType::class, [
                'class'        => Regions::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Region'
            ])
            ->add('countries', EntityType::class, [
                'class'        => Countries::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Country'
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'mapped' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, GIF).',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bottles::class,
        ]);
    }
}
