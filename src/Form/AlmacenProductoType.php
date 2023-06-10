<?php

namespace App\Form;

use App\Entity\AlmacenProducto;
use App\Form\Type\DatalistType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlmacenProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codigo', TextType::class, array(
                'label' => 'Código'
            ))
            ->add('descripcion', TextType::class, array(
                'label' => 'Descripción'
            ))
            ->add('unidadMedida', ChoiceType::class, [
                'choices' => array(
                    'U' => 'U', 'lb' => 'lb', 'kg' => 'kg', 'paq' => 'paq'
                ),
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AlmacenProducto::class,
        ]);
    }
}
