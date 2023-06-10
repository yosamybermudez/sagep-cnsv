<?php

namespace App\Form;

use App\Entity\Cargo;
use App\Entity\Municipio;
use App\Entity\Trabajador;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrabajadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres')
            ->add('apellidos')
            ->add('carneIdentidad')
            ->add('telefono')
            ->add('noExpediente')
            ->add('direccionCarne', TextType::class, [
                'label' => 'Dirección del carné'
            ])
            ->add('direccionResidencia', TextType::class, [
                'label' => 'Dirección de residencia actual'
            ])
            ->add('sexo', ChoiceType::class, ['placeholder' => 'Seleccione', 'choices' => ['Masculino' => 'Masculino', 'Femenino' => 'Femenino']])
            ->add('nivelEducacional', ChoiceType::class, ['placeholder' => 'Seleccione',
                'choices' => [
                    'Medio' => 'Medio',
                    'Medio Superior' => 'Medio Superior',
                    'Superior' => 'Superior'
                ]
            ])
            ->add('fechaAlta', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('municipio', EntityType::class, [
                'placeholder' => 'Seleccione',
                'mapped' => true,
                'choice_label' => 'nombre',
                'class' => Municipio::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->innerJoin('m.provincia', 'p')
                        ->where("p.nombre = 'La Habana'")
                        ->orderBy('m.nombre', 'ASC');
                },
            ])
            ->add('cargo', EntityType::class, array(
                'class' => Cargo::class,
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trabajador::class,
        ]);
    }
}
