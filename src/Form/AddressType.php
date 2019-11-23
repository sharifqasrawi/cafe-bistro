<?php

namespace App\Form;

use App\Entity\SystemSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Address_Street', TextType::class)
            ->add('Address_City', TextType::class)
            ->add('Address_Country', TextType::class)
            ->add('Google_Iframe', TextType::class , [ 'required' => false ])
            ->add('Location_Longitude', TextType::class)
            ->add('Location_Latitude', TextType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemSettings::class,
        ]);
    }
}
