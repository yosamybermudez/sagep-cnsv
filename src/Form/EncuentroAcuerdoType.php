<?php

namespace App\Form;

use App\Entity\EncuentroAcuerdo;
use App\Entity\Persona;
use App\Entity\Trabajador;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EncuentroAcuerdoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('estado', ChoiceType::class, [
                'placeholder' => 'Seleccione',
                'choices' => [
                    'En tiempo' => 'En tiempo',
                    'Cumplido' => 'Cumplido',
                    'Cumplido en parte' => 'Cumplido en parte',
                    'Pospuesto' => 'Pospuesto',
                    'Incumplido' => 'Incumplido',
                    'Cancelado' => 'Cancelado'
                ],
                'mapped' => false,
                'data' => $options['estado']
            ])
            ->add('descripcion', TextareaType::class,[
                'label' => 'Descripción'
            ])
            ->add('observaciones', TextareaType::class,[
                'label' => 'Observaciones',
                'mapped' => false,
                'required' => true,
                'data' => $options['observaciones'] !== "" ? $options['observaciones'] : 'N/E'
            ])
            ->add('periodicidad', ChoiceType::class, [
                'choices' => [
                    'No' => 'No',
                    'Mensual' => 'Mensual',
                    'Trimestral' => 'Trimestral',
                    'Semestral' => 'Semestral',
                    'Anual' => 'Anual'
                ]
            ])
            ->add('fechaCumplimiento', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha de cumplimiento',
                'mapped' => false,
                'data' => $options['fecha_cumplimiento']
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EncuentroAcuerdo::class,
            'estado' => 'En tiempo',
            'fecha_cumplimiento' => null,
            'observaciones' => ''
        ]);
    }
}
