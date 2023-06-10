<?php

namespace App\Form;

use App\Entity\SistemaPermiso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SistemaPermisoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('permisoAgregar')
            ->add('permisoModificar')
            ->add('permisoEliminar')
            ->add('ruta')
            ->add('rol')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SistemaPermiso::class,
        ]);
    }
}
