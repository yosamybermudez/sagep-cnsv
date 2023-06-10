<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePwsdFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("justpassword", PasswordType::class, [
                "label" => "Contraseña actual",
                "required" => true,
                "mapped" => false,
//                "constraints" => [
//                    new NotBlank(["message" => "No puede estar en blanco"]),
//                    new UserPassword(["message" => "Recordar contraseña"])
//                ]
            ])
            ->add("newpassword", RepeatedType::class, [
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
            //'data_class' => Usuario::class,
        ]);
    }
}
