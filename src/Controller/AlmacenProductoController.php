<?php

namespace App\Controller;

use App\Entity\AlmacenProducto;
use App\Form\AlmacenProductoType;
use App\Repository\AlmacenProductoRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gestion/producto")
 */
class AlmacenProductoController extends BaseController
{
    private $rutaTemplates = "gestion/producto/";

    private $entityManager;

    private $productoRepository;

    public function __construct(SistemaRutaRepository $sistemaRutaRepository, SistemaPermisoRepository $sistemaPermisoRepository, EntityManagerInterface $entityManager, AlmacenProductoRepository $almacenProductoRepository)
    {
        parent::__construct($sistemaRutaRepository, $sistemaPermisoRepository, $entityManager);
        $this->productoRepository = $almacenProductoRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="almacen_producto_listar")
     */
    public function listar(AlmacenProductoRepository $productoRepository)
    {
        $productos = $productoRepository->findAll();
        return $this->render($this->rutaTemplates . "index.html.twig", ["productos" => $productos]);
    }

    /**
     * @Route("/json", name="almacen_producto_listar_json")
     */
    public function listarJSON(AlmacenProductoRepository $almacenProductoRepository): Response
    {
        $productos = $almacenProductoRepository->findProductos();
        return new JsonResponse($productos);
    }

    /**
     * @Route("/nuevo",name="almacen_producto_nuevo")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function nuevo(Request $request)
    {
        $producto = new AlmacenProducto($this);
        $form = $this->createForm(AlmacenProductoType::class, $producto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($producto);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("almacen_producto_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="almacen_producto_editar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function editar(AlmacenProducto $producto, Request $request)
    {
        $form = $this->createForm(AlmacenProductoType::class, $producto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($producto);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("almacen_producto_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/eliminar/{id}",name="almacen_producto_eliminar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function eliminar(AlmacenProducto $producto)
    {
        $result = $this->productoRepository->delete($producto);
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/acciongrupal",name="almacen_producto_accion_grupal")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function accionGrupal(Request $request)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $productos = $this->productoRepository->findBy(["id" => $ids]);

        if ($action === 'Eliminar') {
            foreach ($productos as $producto) {
                $this->entityManager->remove($producto);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        $this->entityManager->flush();
        return $this->json(["message" => "success", "nb" => count($productos)]);
    }

}
