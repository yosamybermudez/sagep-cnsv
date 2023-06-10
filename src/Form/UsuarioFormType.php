<?php


namespace App\Form;


use App\Entity\Rol;
use App\Entity\Usuario;
use App\Entity\Trabajador;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsuarioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("username", TextType::class, ["label" => "Usuario"])
            ->add("email", EmailType::class, [
                'label' => 'Correo electrónico'
            ])
            ->add("nombreCompleto", TextType::class, ["label" => "Nombre completo"])
            ->add("trabajadorAsociado", EntityType::class, [
                "mapped" => true,
                "choice_label" => "nombreCompleto",
                "class" => Trabajador::class,
                "placeholder" => "Seleccione",
                "required" => false
            ])
            ->add("role", EntityType::class, [
                "mapped" => false,
                "label" => "Rol del sistema",
                "choice_label" => "nombre",
                "class" => Rol::class,
                "required" => true,
                "placeholder" => "Seleccione",
                "constraints" => [
                    new NotBlank(["message" => "Seleccione un elemento de la lista"]),
                ]
            ])
            ->add("password", RepeatedType::class, [
                "mapped" => false,
//                'invalid_message' => 'La nueva contraseña',//$this->translator->trans('backend.user.new_passwod_must_be'),
                "type" => PasswordType::class,
                "constraints" => [
                    new NotBlank(["message" => "No puede estar en blanco"])
                ],
                "first_options"  => ['label' => "Nueva contraseña"],
                "second_options"  => ['label' => "Confirme la contraseña"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class
        ]);
    }
}
