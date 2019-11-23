<?php

namespace App\Form;

use App\Entity\Gallary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GallaryImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title', TextType::class, [ 'required' => false ])
            ->add('isSlide', CheckboxType::class, [ 'required' => false ])
            ->add('Image', FileType::class, [ 
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
            'data_class' => Gallary::class,
        ]);
    }
}
