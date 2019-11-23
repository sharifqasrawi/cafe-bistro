<?php

namespace App\Form;

use App\Entity\SystemSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LocationHeaderImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('LocationHeaderImage', FileType::class, [ 
                'required' => false, 
                'data_class' => null,
                'constraints' =>  new File([
                'maxSize' => '10000k',
                'mimeTypes' => [
                    'image/*',
                ],
                'mimeTypesMessage' => 'Please upload a valid image file', ])
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemSettings::class,
        ]);
    }
}
