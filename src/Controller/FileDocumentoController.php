<?php


namespace App\Controller;


use App\Entity\FileDocumento;
use App\Entity\Trabajador;
use App\Repository\FileDocumentoRepository;
use App\Response\PdfResponse;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/fileDocumento")
 */
class FileDocumentoController extends BaseController
{
    private $rutaTemplates = "fileDocumento/";
//
//    /**
//     * @Route("/",name="fileDocumento_listar")
//     * @IsGranted("ROLE_ESPECIALISTA")
//     */
//    public function listar(FileDocumentoRepository $fileDocumentoRepository)
//    {
//        $fileDocumentos = $fileDocumentoRepository->findBy([],['fechaEvento' => 'DESC', 'hora' => 'DESC']);
//        return $this->render($this->rutaTemplates . "index.html.twig", ["fileDocumentos" => $fileDocumentos]);
//    }
//
//    /**
//     * @Route("/nuevo",name="fileDocumento_nuevo")
//     */
//    public function nuevo(Request $request, EntityManagerInterface $entityManager)
//    {
//        $fileDocumento = new FileDocumento();
//        $trabajadores = $entityManager->getRepository(Trabajador::class)->findAll();
//        $fileDocumento->setRefEvento($this->nomencladorFileDocumento($entityManager));
//        $form = $this->createForm(FileDocumentoType::class, $fileDocumento);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $invitados = $request->request->get('invitado');
//            $participantes = $request->request->get('chkgrp');
//            $fileDocumento = $form->getData();
//            $fileDocumento->setRefEvento($this->nomencladorFileDocumento($entityManager));
//            if($participantes !== null){
//                foreach ($participantes as $participante){
//                    $fileDocumento->addParticipante($entityManager->getRepository(Trabajador::class)->find($participante));
//                }
//            }
//
//            $inv = [];
//            if($invitados != null)
//            foreach ($invitados as $key => $invitado) {
//                $inv[$key]['nombre'] = explode('-',$invitado)[0];
//                $inv[$key]['cargo'] = explode('-',$invitado)[1];
//                $inv[$key]['entidad'] = explode('-',$invitado)[2];
//                $fileDocumento->setInvitados($inv);
//            }
//            $this->fileDocumento($request,'fileDocumento', $fileDocumento, $entityManager);
//            $this->comentario($request,'fileDocumento', $fileDocumento, $entityManager);
//            $fileDocumento->setRefEvento($this->nomencladorFileDocumento($entityManager));
//            $entityManager->persist($fileDocumento);
//            $entityManager->flush();
//            $this->addFlash("success", "Elemento registrado satisfactoriamente");
//            return $this->redirectToRoute("fileDocumento_listar");
//        }
//        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "trabajadores" => $trabajadores]);
//    }
//
//    /**
//     * @Route("/editar/{id}",name="fileDocumento_editar")
//     */
//    public function editar(FileDocumento $fileDocumento, Request $request, EntityManagerInterface $entityManager)
//    {
//        $trabajadores = $entityManager->getRepository(Trabajador::class)->findAll();
//        $fileDocumento_old = clone $fileDocumento;
//        $form = $this->createForm(FileDocumentoType::class, $fileDocumento);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->fileDocumento($request,'fileDocumento', $fileDocumento, $entityManager);
//            $this->comentario($request,'fileDocumento', $fileDocumento, $entityManager);
//            $invitados = $request->request->get('invitado');
//            $participantes = $request->request->get('chkgrp');
//            $fileDocumento = $form->getData();
//
//            if($participantes !== null){
//                foreach ($participantes as $participante){
//                    $fileDocumento->addParticipante($entityManager->getRepository(Trabajador::class)->find($participante));
//                }
//            }
//            $inv = [];
//            if($invitados != null)
//                foreach ($invitados as $key => $invitado) {
//                    $inv[$key]['nombre'] = explode('-',$invitado)[0];
//                    $inv[$key]['cargo'] = explode('-',$invitado)[1];
//                    $inv[$key]['entidad'] = explode('-',$invitado)[2];
//                    $fileDocumento->setInvitados($inv);
//                }
//            $fileDocumento->setRefEvento($fileDocumento_old->getRefEvento());
//            $entityManager->persist($fileDocumento);
//            $entityManager->flush();
//            $this->addFlash("success", "Elemento modificado satisfactoriamente");
//            return $this->redirectToRoute("fileDocumento_mostrar", ['id' => $fileDocumento->getId()]);
//        }
//        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "trabajadores" => $trabajadores, "fileDocumento" => $fileDocumento]);
//    }
//
//    /**
//     * @Route("/exportar/{formato}/{id}",name="fileDocumento_exportar")
//     */
//    public function exportar(FileDocumento $fileDocumento, Request $request, EntityManagerInterface $entityManager, string $formato){
////        if($formato === 'pdf'){
////            $header = 'header';
////            $html = 'a';
////            $nombre=utf8_decode($this->getUser()->getNombreCompleto()).' - '.date("d-m-Y h:i A");
////            $knpSnappy = new Pdf($this->pdfBinario());
////            return new PdfResponse(
////                $knpSnappy->getOutputFromHtml($html,
////                    array(
////                        'lowquality' => false,
////                        'print-media-type' => true,
////                        'enable-javascript' => true,
////                        'enable-local-file-access' => true,
////                        'encoding' => 'utf-8',
////                        'page-size' => 'Letter',
////                        'outline-depth' => 8,
////                        'orientation' => 'Portrait',
////                        'margin-top'=>25,
////                        'title'=> $fileDocumento->getNombre(),
////                        'header-html'=>$header,
////                        'footer-left'=>$nombre,
////                        'footer-right'=>'Pag. [page] de [toPage]',
////                        'footer-font-size'=>6
////                    )
////                ),
////                $fileDocumento->getRefEvento().'.pdf'
////            );
////        } elseif($formato === 'excel') {
////
////        } else {
////            $this->redirectToRoute('fileDocumento_exportar', array('id' => $fileDocumento->getId(), 'formato' => 'pdf'));
////        }
//        return 0;
//    }

    /**
     * @Route("/eliminar/{id}",name="file_documento_eliminar")
     */
    public function eliminar(FileDocumento $fileDocumento, FileDocumentoRepository $fileDocumentoRepository)
    {
        $url=$fileDocumento->getUrl();
        $result = $fileDocumentoRepository->delete($fileDocumento);
        if($result==true){
            $dir='assets/'.$url;
            if (file_exists($dir)){
//                dd($dir);
                unlink($dir);
            }
        }
        return $this->json(["message" => "success", "value" => $result]);
    }

//    /**
//     * @Route("/mostrar/{id}",name="fileDocumento_mostrar")
//     */
//    public function mostrar(FileDocumento $fileDocumento){
//        return $this->render($this->rutaTemplates . "show.html.twig", ["fileDocumento" => $fileDocumento]);
//    }
//
//    /**
//     * @Route("/acciongrupal",name="fileDocumento_accion_grupal")
//     */
//    public function accionGrupal(Request $request, FileDocumentoRepository $fileDocumentoRepository)
//    {
//        $action = $request->get("action");
//        $ids = $request->get("ids");
//        $fileDocumentos = $fileDocumentoRepository->findBy(["id" => $ids]);
//
//        if ($action == 'Eliminar') {
//            foreach ($fileDocumentos as $fileDocumento) {
//              $fileDocumentoRepository->delete($fileDocumento);
//            }
//        } else {
//            return $this->json(["message" => "error"]);
//        }
//        return $this->json(["message" => "success", "nb" => count($fileDocumentos)]);
//    }
//
//    public function nomencladorFileDocumento(EntityManagerInterface $entityManager){
//
//        $proximo_numero = count($entityManager->getRepository(FileDocumento::class)->findByAnnoActual()) + 1;
//        $cadena = 'ENC-CNSV-';
//
//        if($proximo_numero < 10)
//            $cadena .= "000" . $proximo_numero;
//        if($proximo_numero >= 10 and $proximo_numero < 100)
//            $cadena .= "00" . $proximo_numero;
//        if($proximo_numero >= 100 and $proximo_numero < 1000)
//            $cadena .= "0" . $proximo_numero;
//
//        $cadena .= "-" . date("Y");
//        return $cadena;
//    }
}