<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Nombre del producto',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Descripción',
            ])
            ->add('price', NumberType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Precio',
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock disponible',
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false, 
                'label' => 'Imagen',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar Producto',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,  
        ]);
    }
}
