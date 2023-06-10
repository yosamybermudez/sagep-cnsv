<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusquedaAvanzadaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entidad', ChoiceType::class, ['placeholder' => 'Seleccione',
                'choices' => [
                    'Encuentros' => 'Encuentro',
                    'Acuerdos' => 'EncuentroAcuerdo'
                ]]
            )
            ->add('fechaInicio', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('fechaFin', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
