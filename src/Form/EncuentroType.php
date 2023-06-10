<?php

namespace App\Form;

use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Entity\EncuentroTipo;
use App\Entity\Trabajador;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EncuentroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [

            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción',
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('fechaEvento', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('hora', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Hora inicio'
            ])

            ->add('refEvento', TextType::class, ['label' => 'Referencia de evento', 'attr' => ['disabled' => true ]])
            ->add('estado', ChoiceType::class, [
                'placeholder' => 'Seleccione',
                'choices' =>
                    [
                        'A aprobación del presidente' => 'A aprobación del presidente',
                        'Firmado por el presidente' => 'Firmado por el presidente',
                        'Circulado a los miembros' => 'Circulado a los miembros',
                        'Pendiente de respuesta' => 'Pendiente de respuesta',
                        'Archivado' => 'Archivado'
                    ]
            ])
            ->add('lugar')
            ->add('dirigeEncuentro', TextType::class, [
                'label' => 'Dirige el encuentro'
            ])
            ->add('horaFin', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('tipoEncuentro', EntityType::class, [
                'class' => EncuentroTipo::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione'
            ])
            ->add('participantes', EntityType::class, [
                'class' => Trabajador::class,
                'choice_label' => 'nombreCompleto',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('cantidadTrabajadores', NumberType::class, [
                'label' => 'Deben asisitir (Cantidad)'
            ])
            ->add('fileDocumento', FileType::class, [
                'required' => false,
                'multiple' => true,
                'mapped' => false,
                'label' => 'Seleccione los documentos'
            ])
            ->add('comentario', TextareaType::class, array(
                'required' => false,
                'mapped'=>false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Encuentro::class,
        ]);
    }
}
