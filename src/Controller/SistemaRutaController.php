<?php


namespace App\Controller;


use App\Entity\Cargo;
use App\Entity\SistemaRuta;
use App\Entity\Trabajador;
use App\Entity\Usuario;
use App\Form\CargoType;
use App\Form\SistemaRutaType;
use App\Form\TrabajadorType;
use App\Form\UsuarioFormType;
use App\Repository\CargoRepository;
use App\Repository\MunicipioRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use App\Repository\TrabajadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/config/sistema/ruta")
 * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
 */
class SistemaRutaController extends BaseController
{
    private $rutaTemplates = "config/sistema_ruta/";

    private $entityManager;

    public function __construct(SistemaRutaRepository $sistemaRutaRepository, SistemaPermisoRepository $sistemaPermisoRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct($sistemaRutaRepository, $sistemaPermisoRepository, $entityManager);
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/",name="sistema_ruta_listar")
     */
    public function listar(SistemaRutaRepository $sistemaRutaRepository)
    {
        $sistemaRutas = $sistemaRutaRepository->findAll();
        return $this->render($this->rutaTemplates . "index.html.twig", ["sistema_rutas" => $sistemaRutas]);
    }

    /**
     * @Route("/nuevo",name="sistema_ruta_nuevo")
     */
    public function nuevo(Request $request)
    {
        $sistemaRuta = new SistemaRuta();
        $form = $this->createForm(SistemaRutaType::class, $sistemaRuta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($sistemaRuta);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("sistema_ruta_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="sistema_ruta_editar")
     */
    public function editar(Usuario $user, Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(UsuarioFormType::class, $user, ["translator" => $translator]);
        $form->get('justpassword')->setData($user->getPassword());
        $therole = $this->roleRepository->findOneBy(["identificador" => $user->getRoles()[0]]);
        $form->get('role')->setData($therole);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Role $role */
            $role = $form["role"]->getData();
            $password = $form["justpassword"]->getData();
            $user->setRoles([$role->getIdentificador()]);
            if ($user->getPassword() != $password) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("app_config_usuario_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["userForm" => $form->createView()]);
    }

    /**
     * @Route("/eliminar/{id}",name="sistema_ruta_eliminar")
     */
    public function eliminar(Usuario $user)
    {
        $user = $this->userRepository->delete($user);
        /*$this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');*/
        return $this->json(["message" => "success", "value" => $user->isDeleted()]);
    }

    /**
     * @Route("/acciongrupal",name="sistema_ruta_accion_grupal")
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
        $this->entityManager->flush();
        return $this->json(["message" => "success", "nb" => count($users)]);
    }
}