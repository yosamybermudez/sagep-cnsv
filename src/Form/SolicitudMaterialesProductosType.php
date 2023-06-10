<?php

namespace App\Form;

use App\Entity\AlmacenProducto;
use App\Entity\CentroCosto;
use App\Entity\SolicitudMateriales;
use App\Entity\SolicitudMaterialesProductos;
use App\Entity\Usuarios;
use App\Form\Type\DatalistType;
use App\Repository\AlmacenProductoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudMaterialesProductosType extends AbstractType
{
    private $almacenProductoRepository;

    public function __construct(AlmacenProductoRepository $almacenProductoRepository)
    {
        $this->almacenProductoRepository = $almacenProductoRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dd($options['help_attr']['0']->getId());
//        dd($options['data']->getAlmacen()->getId());
        $builder

//            ->add('producto',EntityType::class, array(
//                'class' => AlmacenProducto::class,
//                'label'=>false,
//                'choice_label' => 'codigoDescripcion',
//                'placeholder' => 'Seleccione',
//                'required'=>true,
//                'attr'=>array('class'=>'producto-select'),
////                'query_builder' => function($er) use ($options) {
////                    return $er->createQueryBuilder('c')
////                        ->where("c.almacen='".$options['help_attr']['0']->getId()."'")
////                        ->orderBy('c.codigo', 'ASC');
////                }
//            ))
            ->add('producto', DatalistType::class, [
                'choices' => $options['productos'],
                'mapped' => false,
                'required' => true
            ])
            ->add('cantidad', TextType::class, array(
                'attr'=>array('class'=>'cantidad-input'),
                'required' => true,
                'label'=>false,
                'mapped' => false
            ))
            ->add('eliminar_fila', ButtonType::class, array(
                'attr' => array(
                    'class' => 'eliminar-fila btn btn-danger text-white glyphicon glyphicon-minus w-100',
                ),
                'label' => ' ',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudMaterialesProductos::class,
            'productos' => null
        ]);
    }
}
