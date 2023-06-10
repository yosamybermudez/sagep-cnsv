<?php

namespace App\Controller;

use App\Entity\SistemaPermiso;
use App\Form\SistemaPermisoType;
use App\Repository\RolRepository;
use App\Repository\SistemaEntidadRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sistema/permiso")
 */
class SistemaPermisoController extends AbstractController
{
    private $rutaTemplates = "config/sistema_permiso/";

    /**
     * @Route("/",name="sistema_permiso_listar")
     */
    public function listar(SistemaEntidadRepository $sistemaEntidadRepository, RolRepository $rolRepository, SistemaPermisoRepository $sistemaPermisoRepository)
    {
        $permisos = $sistemaPermisoRepository->findAll();
        $permisos_array = array();
        foreach ($permisos as $permiso){
            $permisos_array[$permiso->getEntidad()->getId()][$permiso->getRol()->getId()]['agregar'] = $permiso->getPermisoAgregar();
            $permisos_array[$permiso->getEntidad()->getId()][$permiso->getRol()->getId()]['leer'] = $permiso->getPermisoLeer();
            $permisos_array[$permiso->getEntidad()->getId()][$permiso->getRol()->getId()]['modificar'] = $permiso->getPermisoModificar();
            $permisos_array[$permiso->getEntidad()->getId()][$permiso->getRol()->getId()]['eliminar'] = $permiso->getPermisoEliminar();
        }
        return $this->render( $this->rutaTemplates . 'index.html.twig', [
            'sistema_entidades' => $sistemaEntidadRepository->findAll(),
            'roles' => $rolRepository->findAll(),
            'permisos' => $permisos_array
        ]);
    }

    /**
     * @Route("/new", name="sistema_permiso_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sistemaPermiso = new SistemaPermiso();
        $form = $this->createForm(SistemaPermisoType::class, $sistemaPermiso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sistemaPermiso);
            $entityManager->flush();

            return $this->redirectToRoute('sistema_permiso_index');
        }

        return $this->render('sistema_permiso/new.html.twig', [
            'sistema_permiso' => $sistemaPermiso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sistema_permiso_show", methods={"GET"})
     */
    public function show(SistemaPermiso $sistemaPermiso): Response
    {
        return $this->render('sistema_permiso/show.html.twig', [
            'sistema_permiso' => $sistemaPermiso,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sistema_permiso_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SistemaPermiso $sistemaPermiso): Response
    {
        $form = $this->createForm(SistemaPermisoType::class, $sistemaPermiso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sistema_permiso_index');
        }

        return $this->render('sistema_permiso/edit.html.twig', [
            'sistema_permiso' => $sistemaPermiso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sistema_permiso_delete", methods={"POST"})
     */
    public function delete(Request $request, SistemaPermiso $sistemaPermiso): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sistemaPermiso->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sistemaPermiso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sistema_permiso_index');
    }

    /**
     * @Route("/asignar/{entidad}/{rol}/{funcionalidad}",name="app_config_permiso_cambiar", methods={"get","post"})
     */
    public function cambiar(Request $request, $entidad, $rol, $funcionalidad, SistemaPermisoRepository $sistemaPermisoRepository, RolRepository $rolRepository, SistemaEntidadRepository $sistemaEntidadRepository)
    {
        $permiso = $sistemaPermisoRepository->findOneBy(array('rol' => $rol, 'entidad' => $entidad));
        $rolObject = $rolRepository->find($rol);
        $entidadObject = $sistemaEntidadRepository->find($entidad);
        if($permiso === null){
            $permiso = new SistemaPermiso();
            $permiso->setRol($rolObject);
            $permiso->setEntidad($entidadObject);
        }

        $permiso = $sistemaPermisoRepository->cambiarPermiso($permiso, $funcionalidad);

        if($request->getMethod() === 'POST')
        {
            return $this->json(["message" => "success", "value" => $permiso->{'getPermiso' . ucfirst($funcionalidad)}()]);
        } else {
            $this->addFlash('success', 'Rol ' . strtoupper($rolObject->getNombre()) . ' cambio de permisos sobre la funcionalidad '. strtoupper($funcionalidad). ' del ');
            return $this->redirectToRoute('app_config_permiso_listar');
        }
    }
}
