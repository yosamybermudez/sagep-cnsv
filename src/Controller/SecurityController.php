<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\ChangePasswordFormType;
use App\Form\ChangePwsdFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
 * @Route("/acceso_denegado", name="acceso_denegado")
 */
    public function accesoDenegado(Request $request)
    {
        return $this->render('security/acceso_denegado.html.twig', array(
               'mensaje' => $request->cookies->get('mensaje'),
              'code' => $request->cookies->get('code') ? $request->cookies->get('code') : null,
              'detalle' => $request->cookies->get('detalle') ? $request->cookies->get('detalle') : null,
        ));
    }


    /**
     * @Route("/changepasswd", name="app_cambiar_contrasenna")
     */
    public function changePasswd(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $username = $request->query->get('user');
        $usuario = $username ?
            $this->getDoctrine()->getRepository(Usuario::class)->findOneByUsername($username)
            : null;
        $form = $this->createForm(ChangePwsdFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isPasswordValid = $passwordEncoder->isPasswordValid($usuario, $form->get('justpassword')->getData());

            if($isPasswordValid){
                $new_password = $passwordEncoder->encodePassword($usuario,$form->get('newpassword')->getData());
                $usuario->setPassword($new_password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $em->flush();
                $this->addFlash('success', 'La contraseña fue cambiada satisfactoriamente.');
                return $this->redirectToRoute('app_index');
            } else {
                $this->addFlash('error', 'La contraseña anterior no es correcta');
            }
        }

        return $this->render('reset_password/cambiar_contrasena.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(){

    }

}
