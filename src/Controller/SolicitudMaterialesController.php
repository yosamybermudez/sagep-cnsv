<?php

namespace App\Controller;

use App\Entity\AlmacenProducto;
use App\Entity\SolicitudMateriales;
use App\Entity\SolicitudMaterialesProductos;
use App\Repository\AlmacenProductoRepository;
use App\Repository\SolicitudMaterialesProductosRepository;
use App\Repository\SolicitudMaterialesRepository;
use App\Form\SolicitudMaterialesType;
use App\Response\PdfResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/gestion/solicitud_materiales")
 */
class SolicitudMaterialesController extends BaseController
{
    private $rutaTemplates = "gestion/solicitud_materiales/";

    /**
     * @Route("/",name="solicitud_materiales_listar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function listar(SolicitudMaterialesRepository $solicitudMaterialesRepository)
    {
        $solicitudesMateriales = $solicitudMaterialesRepository->findAll();
        return $this->render($this->rutaTemplates . "index.html.twig", ["solicitudesMateriales" => $solicitudesMateriales]);
    }

    /**
     * @Route("/nuevo",name="solicitud_materiales_nuevo")
     */
    public function nuevo(Request $request, EntityManagerInterface $entityManager)
    {
        $solicitud = new SolicitudMateriales($this);

//        for ($i = 0; $i <= 3; $i++){
//            $solicitudMaterialProducto = new SolicitudMaterialesProductos();
//            $solicitud->addSolicitudesMaterialesProducto($solicitudMaterialProducto);
//        }
        //Datos de pryueba
        $solicitud->setOrganismo('MITRANS');
        $solicitud->setEmpresa('Nivel Central');
        $solicitud->setUnidad('8888');
        $solicitud->setCodigoUnidad('8888');
        $solicitud->setAlmacen('Almacén Central');
        $solicitud->setCentroCosto('Grupo Permanente de Trabajo para la Seguridad Vial GPTSV ');
        $solicitud->setCodigoCentroCosto('023');
        $solicitud->setSolicitadoPorFecha(new \DateTime());

        //

        $productosA = $entityManager->getRepository(AlmacenProducto::class)->findAll();
        $productos = [];
        foreach ($productosA as $prod){
            $productos[$prod->getDescripcion()] = $prod->getCodigoDescripcion();
        }

        $form = $this->createForm(SolicitudMaterialesType::class, $solicitud, ['productos' => $productos]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $solicitudesEnviadas = $request->request->get('solicitud_materiales')['solicitudesMaterialesProductos'];
            $solicitud->blankSolicitudesMaterialesProducto();
               do{
                try{
                    foreach ($solicitudesEnviadas as $s){
                        $sol = new SolicitudMaterialesProductos();
                        $sol->setCantidad($s['cantidad']);
                        $producto = $entityManager->getRepository(AlmacenProducto::class)->findOneByDescripcion($s['producto']);
                        if(!$producto){
                            $producto = new AlmacenProducto($this);
                            $producto->setDescripcion($s['producto']);
                            $entityManager->persist($producto);
                        }
                        $sol->setProducto($producto);
                        $solicitud->addSolicitudesMaterialesProducto($sol);
                        $sol->setSolicitudMateriales($solicitud);
                        $entityManager->persist($sol);
                      //  $entityManager->flush();
                    }
                    $entityManager->persist($solicitud);
                    $entityManager->flush();
                    break;
                } catch (UniqueConstraintViolationException $e){
                    throw new \Exception($e->getMessage());
                }
            } while(true);
            $this->fileDocumento($request,'solicitud_materiales', $solicitud, $entityManager);
            $this->comentario($request,'solicitud_materiales', $solicitud, $entityManager);
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("solicitud_materiales_listar");
        }
        return $this->render($this->rutaTemplates . "form2.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/mostrar/{id}",name="solicitud_materiales_mostrar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function mostrar(SolicitudMateriales $solicitudMateriales)
    {
        return $this->render($this->rutaTemplates . "show2.html.twig", ["solicitud" => $solicitudMateriales]);
    }

    /**
     * @Route("/editar/{id}",name="solicitud_materiales_editar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function editar(SolicitudMateriales $solicitudMateriales, Request $request, EntityManagerInterface $entityManager, SolicitudMaterialesProductosRepository $solicitudMaterialesProductosRepository)
    {
        $form = $this->createForm(SolicitudMaterialesType::class, $solicitudMateriales);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $solicitudesEnviadas = $request->request->get('solicitud_materiales')['solicitudesMaterialesProductos'];
            try {
                $solicitudMaterialesProductosRepository->blankSolicitudesMaterialesProducto($solicitudMateriales);
            } catch (\Exception $e){
                throw  new \Exception($e->getMessage());
            }
            dd($solicitudesEnviadas);
            do{
                try{
                    foreach ($solicitudesEnviadas as $s){
                        $sol = new SolicitudMaterialesProductos();
                        $sol->setCantidad($s['cantidad']);
                        $producto = $entityManager->getRepository(AlmacenProducto::class)->findOneByDescripcion($s['producto']);
                        if(!$producto){
                            $producto = new AlmacenProducto($this);
                            $producto->setDescripcion($s['producto']);
                            $entityManager->persist($producto);
                        }
                        $sol->setProducto($producto);
                        $solicitudMateriales->addSolicitudesMaterialesProducto($sol);
                        $sol->setSolicitudMateriales($solicitudMateriales);
                        $entityManager->persist($sol);
                        //  $entityManager->flush();
                    }
                    $entityManager->persist($solicitudMateriales);
                    $entityManager->flush();
                    break;
                } catch (UniqueConstraintViolationException $e){
                    throw new \Exception($e->getMessage());
                }
            } while(true);
            $this->fileDocumento($request,'solicitud_materiales', $solicitudMateriales, $entityManager);
            $this->comentario($request,'solicitud_materiales', $solicitudMateriales, $entityManager);
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("solicitud_materiales_listar");
        }
        return $this->render($this->rutaTemplates . "form2.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/acciongrupal",name="solicitud_materiales_accion_grupal")
     */
    public function accionGrupal(Request $request, SolicitudMaterialesRepository $solicitudMaterialesRepository)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $solicitudes = $solicitudMaterialesRepository->findBy(["id" => $ids]);
        if ($action == 'Eliminar') {
            foreach ($solicitudes as $solicitud) {
                $solicitudMaterialesRepository->delete($solicitud);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($solicitudes)]);
    }



    /**
     * @Route("/eliminar/{id}",name="solicitud_materiales_eliminar")
     */
    public function eliminar(SolicitudMateriales $solicitudMateriales, SolicitudMaterialesRepository $solicitudMaterialesRepository)
    {
        $result = $solicitudMaterialesRepository->delete($solicitudMateriales);
        /*$this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');*/
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     *
     * @Route("/exportar/word/{id}", name="solicitud_materiales_exportar_word")
     */

    public function exportarWordAction(SolicitudMateriales $solicitudMateriales){

        $templateProcessor = new TemplateProcessor('assets/plantillasPhpWord/PlantillaSolicitudesMateriales.docx');

        $templateProcessor->setValue('organismo', $solicitudMateriales->getOrganismo());
        $templateProcessor->setValue('empresa', $solicitudMateriales->getEmpresa());
        $templateProcessor->setValue('unidad', $solicitudMateriales->getUnidad());
        $templateProcessor->setValue('codigo', $solicitudMateriales->getCodigoUnidad());
        $templateProcessor->setValue('almacen', $solicitudMateriales->getAlmacen());
        $templateProcessor->setValue('noOrden', $solicitudMateriales->getNroOrden());
        $templateProcessor->setValue('centroCosto', $solicitudMateriales->getCentroCosto());
        $templateProcessor->setValue('codigoCC', $solicitudMateriales->getCodigoCentroCosto());
        $templateProcessor->setValue('lote', $solicitudMateriales->getNroLote());
        $templateProcessor->setValue('producto', $solicitudMateriales->getProducto());
        $templateProcessor->setValue('otros', $solicitudMateriales->getOtros());
        $templateProcessor->setValue('cargoSoliictadoPor', $solicitudMateriales->getSolicitadoPorCargo());
        $templateProcessor->setValue('nombreApellidosSolicitadoPor', $solicitudMateriales->getSolicitadoPorNombreCompleto());
        $templateProcessor->setValue('cargoRecibidoPor', $solicitudMateriales->getRecibidoPorCargo());
        $templateProcessor->setValue('nombreApellidosRecibidoPor', $solicitudMateriales->getRecibidoPorNombreCompleto());
        $templateProcessor->setValue('NoSolicitud', $solicitudMateriales->getNroSolicitud());
        $templateProcessor->setValue('vale', $solicitudMateriales->getValeEntrega());
        $templateProcessor->setValue('ds', $solicitudMateriales->getSolicitadoPorFecha()?$solicitudMateriales->getSolicitadoPorFecha()->format('d'):'');
        $templateProcessor->setValue('ms', $solicitudMateriales->getSolicitadoPorFecha()?$solicitudMateriales->getSolicitadoPorFecha()->format('m'):'');
        $templateProcessor->setValue('as', $solicitudMateriales->getSolicitadoPorFecha()?$solicitudMateriales->getSolicitadoPorFecha()->format('Y'):'');
        $templateProcessor->setValue('dr', $solicitudMateriales->getRecibidoPorFecha()?$solicitudMateriales->getRecibidoPorFecha()->format('d'):'');
        $templateProcessor->setValue('mr', $solicitudMateriales->getRecibidoPorFecha()?$solicitudMateriales->getRecibidoPorFecha()->format('m'):'');
        $templateProcessor->setValue('ar', $solicitudMateriales->getRecibidoPorFecha()?$solicitudMateriales->getRecibidoPorFecha()->format('Y'):'');


        $templateProcessor->cloneRow('cProducto', count($solicitudMateriales->getSolicitudesMaterialesProductos()));
            $templateProcessor->cloneRow('cProducto1', count($solicitudMateriales->getSolicitudesMaterialesProductos()));
        $count=1;
        foreach ($solicitudMateriales->getSolicitudesMaterialesProductos() as $producto) {
            $templateProcessor->setValue('cProducto#'.$count, $producto->getProducto()->getCodigo());
            $templateProcessor->setValue('descripcion#'.$count, $producto->getProducto()->getDescripcion());
            $templateProcessor->setValue('um#'.$count, $producto->getProducto()->getUnidadMedida());
            $templateProcessor->setValue('cantidad#'.$count, $producto->getCantidad());
            $templateProcessor->setValue('cProducto1#'.$count, $producto->getProducto()->getCodigo());
            $templateProcessor->setValue('descripcion1#'.$count, $producto->getProducto()->getDescripcion());
            $templateProcessor->setValue('um1#'.$count, $producto->getProducto()->getUnidadMedida());
            $templateProcessor->setValue('cantidad1#'.$count, $producto->getCantidad());
            $count++;
        }
        $temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
        $templateProcessor->saveAs($temp_file);
        $response=new BinaryFileResponse($temp_file);
        $response->headers->set('Content-Type','application/msword');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            utf8_decode('Solicitud Materiales').'.docx'
        );
       return $response;

    }


}
