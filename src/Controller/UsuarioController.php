<?php


namespace App\Controller;


use App\Entity\Rol;
use App\Entity\Usuario;
use App\Form\ChangePwsdFormType;
use App\Form\UsuarioFormType;
use App\Repository\RolRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/configuracion/usuario")
 * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
*/
class UsuarioController extends BaseController
{
    private $userRepository;
    private $passwordEncoder;

    private $entityManager;
    private $roleRepository;

    private $rutaTemplates = "config/usuario/";

    public function __construct(UsuarioRepository $userRepository, RolRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->roleRepository = $roleRepository;
    }

    public function fakepswd(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMINISTRADOR_SISTEMA");
        $user = new User();
        $user->setValid(true)
            ->setDeleted(false)
            ->setEmail("mam@ddd.com")
            ->setNombreCompleto("nom comp")
            ->setUsername("mamless")
            ->setRoles(["ROLE_ADMINISTRADOR_SISTEMA"])
            ->setPassword($this->passwordEncoder->encodePassword($user, $request->get("password")));
        // $user = $this->userRepository->saveUser($user);
        return $this->json(["id" => $user->getId(), "password" => $user->getPassword(), "decode" => $this->passwordEncoder->isPasswordValid($user, 1)]);
    }

    /**
     * @Route("/",name="usuario_listar")
     */
    public function listar()
    {
        $usuarios = $this->userRepository->findAll();
        return $this->render($this->rutaTemplates . "index.html.twig", ["usuarios" => $usuarios]);
    }

    /**
     * @Route("/nuevo",name="usuario_nuevo")
     */
    public function nuevo(Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(UsuarioFormType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//
            /** @var  User $user */
            $user = $form->getData();
            /** @var Role $role */
            $password = $form["password"]->getData();
            $role = $form["role"]->getData();
            $user->setValid(true)
                ->setDeleted(false)
                ->setAdmin(false)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password))
                ->setRoles([$role->getIdentificador()]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("usuario_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="usuario_editar")
     */
    public function editar(Usuario $user, Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(UsuarioFormType::class, $user);
        $form->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => false,
            'invalid_message' => 'Las contraseñas no coinciden.',
            'options' => ['attr' => ['class' => 'form-control password-field']],
            'first_options'  => ['label' => 'Contraseña'],
            'second_options' => ['label' => 'Repetir contraseña'],
        ]);
        $form->get('password')->setData($user->getPassword());
        $therole = $this->roleRepository->findOneBy(["identificador" => $user->getRoles()[0]]);
        $form->get('role')->setData($therole);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Role $role */
            $role = $form["role"]->getData();
            $password = $form["password"]->getData();
            $user->setRoles([$role->getIdentificador()]);
            if ($user->getPassword() === $password and $password !== null) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("usuario_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/mostrar/{id}",name="usuario_mostrar")
     */
    public function mostrar(Usuario $user, Request $request)
    {
        return $this->render($this->rutaTemplates . "show.html.twig", ["usuario" => $user]);
    }

    /**
     * @Route("/cambiarestado/{id}",name="usuario_cambiar_estado",methods={"post"})
     */
    public function cambiarEstado(Usuario $user)
    {
//        if($user->getUsername() === 'admin'){
//            return $this->json(["message" => "error", "value" => "El usuario 'admin' no se puede desactivar"]);
//        }
        $user = $this->userRepository->changeValidite($user);
        return $this->json(["message" => "success", "value" => $user->isValid()]);
    }

    /**
     * @Route("/eliminar/{id}",name="usuario_eliminar")
     */
    public function eliminar(Usuario $user)
    {
        $user = $this->userRepository->delete($user);
        /*$this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');*/
        return $this->json(["message" => "success", "value" => $user->isDeleted()]);
    }

    /**
     * @Route("/cambiarcontrasena",name="usuario_cambiar_contrasena")
     */
    public function cambiarContrasena(Request $request, TranslatorInterface $translator)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePwsdFormType::class, $user, ["translator" => $translator]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password =  $form["justpassword"]->getData();
            $newPassword = $form["newpassword"]->getData();

            if ($this->passwordEncoder->isPasswordValid($user, $password)) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));
            } else {
                $this->addFlash("error", $translator->trans('backend.user.new_passwod_must_be'));
                return $this->render("admin/params/changeMdpForm.html.twig", ["passwordForm" => $form->createView()]);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash("success", $translator->trans('backend.user.changed_password'));
            return $this->redirectToRoute("app_index");
        }
        return $this->render("admin/params/changeMdpForm.html.twig", ["passwordForm" => $form->createView()]);
    }

    /**
     * @Route("/acciongrupal",name="usuario_accion_grupal")
     */
    public function accionGrupal(Request $request, TranslatorInterface $translator)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $users = $this->userRepository->findBy(["id" => $ids]);

        if ($action == $translator->trans('backend.user.deactivate')) {
            foreach ($users as $user) {
                $user->setValid(false);
                $this->entityManager->persist($user);
            }
        } else if ($action == $translator->trans('backend.user.Activate')) {
            foreach ($users as $user) {
                $user->setValid(true);
                $this->entityManager->persist($user);
            }
        } else if ($action == $translator->trans('backend.user.delete')) {
            foreach ($users as $user) {
                $user->setDeleted(true);
                $this->entityManager->persist($user);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
    }
}