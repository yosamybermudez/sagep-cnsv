<?php

namespace App\Controller;

use App\Entity\SistemaEntidad;
use App\Entity\SistemaPermiso;
use App\Entity\SistemaRuta;
use App\Entity\Usuario;
use App\Form\SistemaEntidadType;
use App\Form\SistemaPermisoType;
use App\Form\SistemaRutaType;
use App\Form\UsuarioFormType;
use App\Repository\RolRepository;
use App\Repository\SistemaEntidadRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/config/sistema/entidad")
 */
class SistemaEntidadController extends AbstractController
{
    private $rutaTemplates = "config/sistema_entidad/";

    /**
     * @Route("/",name="sistema_entidad_listar")
     */
    public function listar(SistemaEntidadRepository $sistemaEntidadRepository)
    {
        $entidades = $sistemaEntidadRepository->findAll();

        return $this->render( $this->rutaTemplates . 'index.html.twig', [
            'sistema_entidades' => $entidades
        ]);
    }

    /**
     * @Route("/nuevo",name="sistema_entidad_nuevo")
     */
    public function nuevo(Request $request)
    {
        $sistemaEntidad = new SistemaEntidad();
        $form = $this->createForm(SistemaEntidadType::class, $sistemaEntidad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($sistemaEntidad);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("sistema_entidad_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="sistema_entidad_editar")
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
     * @Route("/eliminar/{id}",name="sistema_entidad_eliminar")
     */
    public function eliminar(Usuario $user)
    {
        $user = $this->userRepository->delete($user);
        /*$this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');*/
        return $this->json(["message" => "success", "value" => $user->isDeleted()]);
    }

    /**
     * @Route("/acciongrupal",name="sistema_entidad_accion_grupal")
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
