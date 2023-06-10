<?php

namespace App\Form;

use App\Entity\SolicitudMateriales;
use App\Entity\Trabajador;
use Container4KbZixc\getSolicitudMaterialesProductosTypeService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SolicitudMaterialesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('organismo')
           ->add('empresa')
           ->add('unidad')
            ->add('codigoUnidad')
            ->add('almacen')
            ->add('nroOrden')
            ->add('centroCosto')
            ->add('codigoCentroCosto')
            ->add('nroLote')
            ->add('producto')
            ->add('otros')
            ->add('solicitadoPorNombreCompleto')
            ->add('solicitadoPorCargo')
            ->add('solicitadoPorFecha', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('recibidoPorNombreCompleto')
            ->add('recibidoPorCargo')
            ->add('recibidoPorFecha', DateType::class, [
                'widget' => 'single_text',
                'required'=>false
            ])
            ->add('nroSolicitud')
            ->add('valeEntrega')
            ->add('comentario', TextareaType::class, array(
                'required' => false,
                'mapped'=>false
            ))
            ->add('fileDocumento', FileType::class, array(
                'label' => 'Documento',
                'required' => false,
                'mapped'=>false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                    ])
                ]
            ))
            ->add('solicitudesMaterialesProductos', CollectionType::class, array(
                'entry_type' => SolicitudMaterialesProductosType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => array('productos' => $options['productos'])
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudMateriales::class,
            'productos' => null
        ]);
    }
}
