<?php


namespace App\Form;

use App\Entity\Pregunta;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PreguntaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enunciado', TextType::class)
            ->add('a', TextType::class)
            ->add('b', TextType::class)
            ->add('c', TextType::class, ['required' => false])
            ->add('d', TextType::class, ['required' => false])
            ->add('oCorrecta', ChoiceType::class, [
                'choices' => [
                    'A' => 'a',
                    'B' => 'b',
                    'C' => 'c',
                    'D' => 'd',
                ],
            ])
            ->add('activa', CheckboxType::class, [
                'required' => false, // No es obligatorio
                'label' => 'Activa', // Etiqueta del checkbox
                'mapped' => true, // Se mapea a la propiedad activa
                'value' => true, // Valor por defecto si se marca el checkbox (true)
            ])
            ->add('fInicio', DateTimeType::class, ['required' => false])
            ->add('fFin', DateTimeType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pregunta::class,
        ]);
    }
}
