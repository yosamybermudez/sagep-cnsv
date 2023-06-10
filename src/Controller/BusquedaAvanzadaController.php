<?php

namespace App\Controller;

use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Form\BusquedaAvanzadaType;
use App\Form\EncuentroAcuerdoCambiarEstadoType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class BusquedaAvanzadaController extends AbstractController
{
    /**
     * @Route("/busqueda_avanzada", name="app_busqueda_avanzada")
     */
    public function busquedaAvanzada(Request $request, EntityManagerInterface $entityManager){
        $form = $this->createForm(BusquedaAvanzadaType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fechaInicio = $form->get('fechaInicio')->getData();
            $fechaFin = $form->get('fechaFin')->getData();
            switch ($form->get('entidad')->getData()){
                case 'Encuentro' : {
                    $result = $entityManager->getRepository(Encuentro::class)->findByDates($fechaInicio, $fechaFin);
                    $entidad = 'Encuentro';
                    break;
                }
                case 'EncuentroAcuerdo' : {
                    $result = $entityManager->getRepository(EncuentroAcuerdo::class)->findByDates($fechaInicio, $fechaFin);
                    $entidad = 'EncuentroAcuerdo';
                    break;
                }
                default: {
                    $result = null;
                }
            }
            $acuerdoEstados = ['Cumplido',
                'Incumplido',
                'Cumplido en parte',
                'Pospuesto',
                'Vigente',
                'En tiempo'];
            $formCambiarEstado = $this->createForm(EncuentroAcuerdoCambiarEstadoType::class);
            return $this->render("busqueda_avanzada\\form.html.twig", [
                "form" => $form->createView(),
                "acuerdoEstados" => $acuerdoEstados,
                "entidad" => $entidad,
                "formCambiarEstado" => $formCambiarEstado->createView(),
                "fechaInicio" => $fechaInicio,
                "fechaFin" => $fechaFin,
                "result" => $result]);
        }
        return $this->render("busqueda_avanzada/form.html.twig", ["form" => $form->createView()]);
    }

    public function exportarEncuentrosExcel(Spreadsheet $phpExcelObject, $result){
        $phpExcelObject->getActiveSheet()->setTitle('Encuentros');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Fecha y hora')
            ->setCellValue('B1', 'Nombre')
            ->setCellValue('c1', 'Tipo')
            ->setCellValue('d1', 'Lugar')
            ->setCellValue('E1', 'Dirige')
            ->setCellValue('F1', 'Hora fin');
        $fila = 2;
        foreach ($result as $elem){
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $elem->getFechaEvento()->format('d-m-Y') . " " . $elem->getHora()->format('h:i:s a'))
                ->setCellValue('B' . $fila, $elem->getNombre())
                ->setCellValue('C' . $fila, $elem->getTipoEncuentro() ? $elem->getTipoEncuentro()->getNombre() : '')
                ->setCellValue('D' . $fila, $elem->getLugar())
                ->setCellValue('E' . $fila, $elem->getDirigeEncuentro())
                ->setCellValue('F' . $fila, $elem->getHoraFin() ? $elem->getHoraFin()->format('h:i:s a') : '');
            $fila++;
        }
        return $phpExcelObject;
    }

    public function exportarAcuerdosExcel(Spreadsheet $phpExcelObject, $result){
        $phpExcelObject->getActiveSheet()->setTitle('Acuerdos');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Ref. Encuentro')
            ->setCellValue('B1', 'No. Acuerdo')
            ->setCellValue('C1', 'Fecha')
            ->setCellValue('D1', 'Fecha de cumplimiento')
            ->setCellValue('E1', 'Descripción')
            ->setCellValue('F1', 'Estado')
            ->setCellValue('G1', 'Periodicidad')
            ->setCellValue('H1', 'Motivo de cancelación')
            ->setCellValue('I1', 'Personas asociadas');
        $fila = 2;
        foreach ($result as $elem){
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $elem->getEncuentro()->getRefEvento())
                ->setCellValue('B' . $fila, $elem->getNoAcuerdo())
                ->setCellValue('C' . $fila, $elem->getFechaInicio() ? $elem->getFechaInicio()->format('d-m-Y') : '')
                ->setCellValue('D' . $fila, $elem->getUltimaFechaCumplimiento() ? $elem->getUltimaFechaCumplimiento()->format('d-m-Y') : '')
                ->setCellValue('E' . $fila, $elem->getDescripcion())
                ->setCellValue('F' . $fila, $elem->getUltimoEstado())
                ->setCellValue('G' . $fila, $elem->getPeriodicidad())
                ->setCellValue('H' . $fila, $elem->getUltimasObservaciones() ?: '-')
                ->setCellValue('I' . $fila,
                    $elem->getEncuentroAcuerdoPersonaAsociadas() ? $elem->getEncuentroAcuerdoPersonaAsociadasToString() : '-');
            $fila++;
        }
        return $phpExcelObject;
    }

    /**
     * @Route("/busqueda_avanzada/exportar/{entidad}/{formato}", name="app_busqueda_avanzada_exportar")
     */
    public function exportarBusquedaAvanzada(Request $request, EntityManagerInterface $entityManager, string $formato, string $entidad)
    {
        $req = $request->query;
        $fecha_param = $req->get('fecha');
        if (!$fecha_param) {
            $fechaInicio = $req->get('inicio') ? new \DateTime($req->get('inicio')) : null;
            $fechaFin = $req->get('fin') ? new \DateTime($req->get('fin')) : null;
        } else {
            $fechaInicio = new \DateTime($fecha_param);
            $fechaFin = new \DateTime($fecha_param);
        }
        //Exportar Excel
        if($formato === 'excel'){
            $title = '';
            $phpExcelObject = new Spreadsheet();
            $phpExcelObject->getProperties()->setCreator("CNSV")
                ->setLastModifiedBy($this->getUser()->getUsername())
                ->setTitle($title)
                ->setKeywords(date("d-m-Y h:i A") . 'CNSV');

            //Condicion para exportar los distintos tipos de entidades
            //Cada tipo tiene un metodo donde se le definen los datos que se mostraran en el EXCEL
            switch ($entidad){
                case 'encuentro': {
                    $title = 'Encuentros';
                    $result = $entityManager->getRepository(Encuentro::class)->findByDates($fechaInicio, $fechaFin);
                    $phpExcelObject = $this->exportarEncuentrosExcel($phpExcelObject, $result);
                    break;
                }
                case 'acuerdo' : {
                    $title = 'Acuerdos';
                    $result = $entityManager->getRepository(EncuentroAcuerdo::class)->findByDates($fechaInicio, $fechaFin);
                    $phpExcelObject = $this->exportarAcuerdosExcel($phpExcelObject, $result);
                    break;
                }
                default: {
                    dd('revisar aqui');
                    break;
                }
            }
            // create the writer
            $writer = new Xls($phpExcelObject);
            // create the response
            $response =  new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );

            if ($fechaInicio === null and $fechaFin !== null) {
                $fecha = 'hasta el ' . $fechaFin->format('d/m/Y');
            } elseif ($fechaInicio !== null && $fechaFin === null) {
                $fecha = 'desde el ' . $fechaInicio->format('d/m/Y');
            } elseif ($fechaInicio === null && $fechaFin === null) {
                $fecha = 'Todas';
            } elseif ($fechaInicio->format('d/m/Y') === $fechaFin->format('d/m/Y') and $fechaInicio->format('d/m/Y') !== $hoy->format('d/m/Y')) {
                $fecha = $fechaInicio->format('d/m/Y');
            } elseif ($fechaInicio->format('d/m/Y') === $fechaFin->format('d/m/Y') and $fechaInicio->format('d/m/Y') === $hoy->format('d/m/Y')) {
                $fecha = 'Hoy';
            } else {
                $fecha = $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y');
            }

            $title .= ' - ' . str_replace('/','_', $fecha);

            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                //$title . ' - '.date("d-m-Y h:i A").'.xls'
                $title . '.xls'
            );
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);

            return $response;
        }
        // Exportar PDF
        elseif($formato === 'pdf'){

        }
        //Redireccionar para exportar EXCEL
        else {
            $this->redirectToRoute('app_busqueda_avanzada_exportar', array('formato' => 'excel', 'entidad' => $entidad, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin));
        }
    }
}
