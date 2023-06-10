<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EncuentroAcuerdoCambiarEstadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estado', ChoiceType::class, [
                'placeholder' => 'Seleccione',
                'choices' => [
                    'Cumplido' => 'Cumplido',
                    'Incumplido' => 'Incumplido',
                    'Cumplido en parte' => 'Cumplido en parte',
                    'Pospuesto' => 'Pospuesto',
                    'En tiempo' => 'En tiempo'
                ]
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
