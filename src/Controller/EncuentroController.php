<?php


namespace App\Controller;


use App\Entity\Encuentro;
use App\Entity\EncuentroTipo;
use App\Entity\Trabajador;
use App\Form\EncuentroType;
use App\Repository\EncuentroAcuerdoRepository;
use App\Repository\EncuentroRepository;
use App\Response\PdfResponse;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/gestion/encuentro")
 */
class EncuentroController extends BaseController
{
    private $rutaTemplates = "gestion/encuentro/";

    /**
     * @Route("/",name="encuentro_listar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function listar(EncuentroRepository $encuentroRepository)
    {
        $encuentros = $encuentroRepository->findBy([],['fechaEvento' => 'DESC', 'hora' => 'DESC']);
        return $this->render($this->rutaTemplates . "index.html.twig", ["encuentros" => $encuentros]);
    }

    /**
     * @Route("/nuevo",name="encuentro_nuevo")
     */
    public function nuevo(Request $request, EntityManagerInterface $entityManager)
    {
        $encuentro = new Encuentro();
        $trabajadores = $entityManager->getRepository(Trabajador::class)->findAll();
        $encuentro->setRefEvento($this->nomencladorEncuentro($entityManager));
        $form = $this->createForm(EncuentroType::class, $encuentro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invitados = $request->request->get('invitado');
            $participantes = $request->request->get('chkgrp');
            $encuentro = $form->getData();
            $encuentro->setRefEvento($this->nomencladorEncuentro($entityManager));
            if($participantes !== null){
                foreach ($participantes as $participante){
                    $encuentro->addParticipante($entityManager->getRepository(Trabajador::class)->find($participante));
                }
            }

            $inv = [];
            if($invitados != null)
            foreach ($invitados as $key => $invitado) {
                $inv[$key]['nombre'] = explode('-',$invitado)[0];
                $inv[$key]['cargo'] = explode('-',$invitado)[1];
                $inv[$key]['entidad'] = explode('-',$invitado)[2];
                $encuentro->setInvitados($inv);
            }
            $this->fileDocumento($request,'encuentro', $encuentro, $entityManager);
            $this->comentario($request,'encuentro', $encuentro, $entityManager);
            $encuentro->setRefEvento($this->nomencladorEncuentro($entityManager));
            $entityManager->persist($encuentro);
            $entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("encuentro_mostrar", ['id' => $encuentro->getId()]);
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "trabajadores" => $trabajadores]);
    }

    /**
     * @Route("/editar/{id}",name="encuentro_editar")
     */
    public function editar(Encuentro $encuentro, Request $request, EntityManagerInterface $entityManager)
    {

        $trabajadores = $entityManager->getRepository(Trabajador::class)->findAll();
        $encuentro_old = clone $encuentro;
        $form = $this->createForm(EncuentroType::class, $encuentro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encuentro->setRefEvento($encuentro_old->getRefEvento());
            $this->fileDocumento($request,'encuentro', $encuentro, $entityManager);
            $this->comentario($request,'encuentro', $encuentro, $entityManager);
            $invitados = $request->request->get('invitado');
            $participantes = $request->request->get('chkgrp');

            $encuentro = $form->getData();

            if($participantes !== null){
                foreach ($participantes as $participante){
                    $persona = $entityManager->getRepository(Trabajador::class)->find($participante);
                    $persona->addEncuentro($encuentro);
                    $encuentro->addParticipante($persona);
                    $entityManager->persist($persona);
                }
            }

            $inv = [];
            if($invitados != null)
                foreach ($invitados as $key => $invitado) {
                    $inv[$key]['nombre'] = explode('-',$invitado)[0];
                    $inv[$key]['cargo'] = explode('-',$invitado)[1];
                    $inv[$key]['entidad'] = explode('-',$invitado)[2];
                    $encuentro->setInvitados($inv);
                }

            $entityManager->persist($encuentro);
            $entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("encuentro_mostrar", ['id' => $encuentro->getId()]);
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "trabajadores" => $trabajadores, "encuentro" => $encuentro]);
    }

    /**
     * @Route("/exportar/{formato}/{id}",name="encuentro_exportar")
     */
    public function exportar(Encuentro $encuentro, Request $request, EntityManagerInterface $entityManager, string $formato){
//        if($formato === 'pdf'){
//            $header = 'header';
//            $html = 'a';
//            $nombre=utf8_decode($this->getUser()->getNombreCompleto()).' - '.date("d-m-Y h:i A");
//            $knpSnappy = new Pdf($this->pdfBinario());
//            return new PdfResponse(
//                $knpSnappy->getOutputFromHtml($html,
//                    array(
//                        'lowquality' => false,
//                        'print-media-type' => true,
//                        'enable-javascript' => true,
//                        'enable-local-file-access' => true,
//                        'encoding' => 'utf-8',
//                        'page-size' => 'Letter',
//                        'outline-depth' => 8,
//                        'orientation' => 'Portrait',
//                        'margin-top'=>25,
//                        'title'=> $encuentro->getNombre(),
//                        'header-html'=>$header,
//                        'footer-left'=>$nombre,
//                        'footer-right'=>'Pag. [page] de [toPage]',
//                        'footer-font-size'=>6
//                    )
//                ),
//                $encuentro->getRefEvento().'.pdf'
//            );
//        } elseif($formato === 'excel') {
//
//        } else {
//            $this->redirectToRoute('encuentro_exportar', array('id' => $encuentro->getId(), 'formato' => 'pdf'));
//        }
        return 0;
    }

    /**
     * @Route("/eliminar/{id}",name="encuentro_eliminar")
     */
    public function eliminar(Encuentro $encuentro, EncuentroRepository $encuentroRepository)
    {
        $result = $encuentroRepository->delete($encuentro);
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/mostrar/{id}/confirmar_cambios",name="encuentro_mostrar_confirmar_cambios")
     */
    public function mostrarConfirmarCambios(Encuentro $encuentro, EncuentroAcuerdoRepository $acuerdoRepository){
        $this->addFlash('success', 'Elementos modificados satisfactoriamente');
        return $this->redirectToRoute('encuentro_mostrar', ["id" => $encuentro->getId()]);
    }

    /**
     * @Route("/mostrar/{id}",name="encuentro_mostrar")
     */
    public function mostrar(Encuentro $encuentro, EncuentroAcuerdoRepository $acuerdoRepository){
        $acuerdosRevisar = $acuerdoRepository->findAcuerdosARevisar($encuentro);
        $acuerdosRevisados = $acuerdoRepository->findAcuerdosRevisados($encuentro);
        $acuerdosNuevos = $acuerdoRepository->findAcuerdosNuevos($encuentro);

        //dd($acuerdosRevisar[0]->getEncuentroAcuerdoTrazabilidads()->toArray());
        // Acuerdos que esten en tiempo. con fecha anterior a la fecha en la que se realiza el encuentro.
        return $this->render($this->rutaTemplates . "show.html.twig", ["encuentro" => $encuentro, "acuerdosRevisar" => $acuerdosRevisar, "acuerdosRevisados" => $acuerdosRevisados, "acuerdosNuevos" => $acuerdosNuevos]);
    }

    /**
     * @Route("/mostrar/{id}/chequeo_acuerdos",name="encuentro_mostrar_chequeo_acuerdos")
     */
    public function chequeoAcuerdos(Encuentro $encuentro, EncuentroAcuerdoRepository $acuerdoRepository){
        $acuerdos = $acuerdoRepository->findAcuerdosARevisar($encuentro);
        return $this->render($this->rutaTemplates . "chequeoAcuerdos.html.twig", ["encuentro" => $encuentro, "acuerdosRevisar" => $acuerdos]);
    }

    /**
     * @Route("/acciongrupal",name="encuentro_accion_grupal")
     */
    public function accionGrupal(Request $request, EncuentroRepository $encuentroRepository, BusquedaAvanzadaController $controller)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $encuentros = $encuentroRepository->findBy(["id" => $ids]);

        if ($action == 'Eliminar') {
            foreach ($encuentros as $encuentro) {
              $encuentroRepository->delete($encuentro);
            }
        } elseif($action == 'Exportar') {
            $phpExcelObject = new Spreadsheet();
            $title = 'Encuentros';
            $phpExcelObject = $controller->exportarEncuentrosExcel($phpExcelObject, $encuentros);
//            $phpExcelObject = $this->exportarEncuentrosExcel($phpExcelObject, $encuentros);

            $writer = new Xls($phpExcelObject);
            ob_start();
            $writer->save('php://output');
            $data = ob_get_contents();
            ob_end_clean();

            return $this->json(["message" => "success", "nb" => ["filename" => $title,"href" => "data:application/vnd.ms-excel;base64,".base64_encode($data)]]);


        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($encuentros)]);
    }

    public function nomencladorEncuentro(EntityManagerInterface $entityManager){

        $proximo_numero = count($entityManager->getRepository(Encuentro::class)->findByAnnoActual()) + 1;
        $cadena = 'ENC-CNSV-';

        if($proximo_numero < 10)
            $cadena .= "000" . $proximo_numero;
        if($proximo_numero >= 10 and $proximo_numero < 100)
            $cadena .= "00" . $proximo_numero;
        if($proximo_numero >= 100 and $proximo_numero < 1000)
            $cadena .= "0" . $proximo_numero;

        $cadena .= "-" . date("Y");
        return $cadena;
    }


}