<?php

namespace App\Form;

use App\Entity\SistemaModulo;
use App\Entity\SistemaRuta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SistemaRutaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('icono')
            ->add('enlace')
            ->add('modulo', EntityType::class, array(
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione',
                'class' => SistemaModulo::class
            ))
            ->add('modulo_nuevo', TextType::class, array(
                'mapped' => false,
                'label' => 'Agregar módulo',
                'required' => false
            ))
            ->add('entidad', EntityType::class, array(
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione',
                'class' => SistemaRuta::class,
                'required' => false
            ))
            ->add('entidad_nueva', TextType::class, array(
                'mapped' => false,
                'label' => 'Agregar entidad',
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SistemaRuta::class,
        ]);
    }
}
