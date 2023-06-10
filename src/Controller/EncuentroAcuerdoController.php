<?php


namespace App\Controller;


use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Entity\EncuentroAcuerdoPeriodico;
use App\Entity\EncuentroAcuerdoPersonaAsociada;
use App\Entity\EncuentroAcuerdoTrazabilidad;
use App\Entity\Municipio;
use App\Entity\Persona;
use App\Entity\Trabajador;
use App\Entity\Usuario;
use App\Form\EncuentroAcuerdoType;
use App\Form\EncuentroType;
use App\Form\MunicipioType;
use App\Form\UsuarioFormType;
use App\Repository\EncuentroAcuerdoRepository;
use App\Repository\EncuentroAcuerdoTrazabilidadRepository;
use App\Repository\EncuentroRepository;
use App\Repository\MunicipioRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/gestion/acuerdo")
 */
class EncuentroAcuerdoController extends BaseController
{
    private $rutaTemplates = "gestion/acuerdo/";

    /**
     * @Route("/operacion/exportar-acuerdos",name="acuerdo_exportar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function exportar($acuerdos = null){
        $title = '';
        $phpExcelObject = new Spreadsheet();
        $phpExcelObject->getProperties()->setCreator("CNSV")
            ->setLastModifiedBy($this->getUser()->getUsername())
            ->setTitle($title)
            ->setKeywords(date("d-m-Y h:i A") . 'CNSV');

        //Condicion para exportar los distintos tipos de entidades
        //Cada tipo tiene un metodo donde se le definen los datos que se mostraran en el EXCEL

        $title = 'Acuerdos';
        $result = $this->getDoctrine()->getRepository(EncuentroAcuerdo::class)->findAll();
        $phpExcelObject = $this->exportarAcuerdosExcel($phpExcelObject, $acuerdos ?: $result);


        // create the writer
        $writer = new Xls($phpExcelObject);
        // create the response
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );




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

    /**
     * @Route("/",name="acuerdo_listar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function listar(Request $request, EncuentroAcuerdoRepository $encuentroAcuerdoRepository, ChartBuilderInterface $chartBuilder)
    {
        $title = "Todos los acuerdos";
        if($request->getMethod() === 'POST' and $request->request->has('submit')) {
            $fechaInicio = $request->request->get('fecha_inicio');
            $fechaFin = $request->request->get('fecha_fin');
            $accion = $request->request->get('filtro');
            switch ($accion) {
                case "Fecha de registro":
                    {
                        $accion_aux = "registrados";
                        break;
                    }
                case "Fecha de registro":
                    {
                        $accion_aux = "a cumplir";
                        break;
                    }
                default:
                    {
                        $accion_aux = "";
                        break;
                    }
            }
            $acuerdos = $encuentroAcuerdoRepository->findByDatesFiltro(new \DateTime($request->request->get('fecha_inicio')), new \DateTime($request->request->get('fecha_fin')),$request->request->get('filtro'));
            if($fechaInicio !== '' && $fechaFin !== ''){
                $title = 'Acuerdos {0} desde {1} hasta {2}';
                $title = str_replace('{0}', $accion_aux,$title);
                $title = str_replace('{1}', $fechaInicio ,$title);
                $title = str_replace('{2}', $fechaFin,$title);
             //   dd($title);
            } elseif($fechaFin === '') {
                $title = 'Acuerdos {0} desde {1}';
                $title = str_replace('{0}', $accion_aux,$title);
                $title = str_replace('{1}', $fechaInicio ,$title);
                $title = str_replace('{2}', $fechaFin,$title);
                //
            } else {
                $title = 'Acuerdos {0} hasta {2}';
                $title = str_replace('{0}', $accion_aux,$title);
                $title = str_replace('{2}', $fechaFin,$title);
                //
            }
//            $acuerdosAtrasados = $encuentroAcuerdoRepository->updateAcuerdosAtrasados();
        } else {
            $acuerdos = $encuentroAcuerdoRepository->findBy([], ['fechaInicio' => 'DESC']);
        }

        $acuerdosEstado = new ArrayCollection();
        foreach ($acuerdos as $item){
            $acuerdosEstado[$item->getUltimoEstado()] += 1;
        }

        $chart_labels = [];
        $chart_data = [];

        foreach ($acuerdosEstado as $key => $value){
            $chart_labels[] = $key;
            $chart_data[] = $value;
        }

        dump($chart_data);


        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => $chart_labels,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    'data' => $chart_data,
                ],
            ],
        ]);
        return $this->render($this->rutaTemplates . "index.html.twig",
            [
                "acuerdos" => $acuerdos,
                "title" => $title,
                "chart" => $chart
            ]);
    }

//    /**
//     * @Route("/nuevo/encuentro={encuentro}",name="acuerdo_nuevo")
//     */
//    public function nuevo(Request $request, EntityManagerInterface $entityManager, Encuentro $encuentro)
//    {
//        $acuerdo = new EncuentroAcuerdo();
//        $trabajadores = $entityManager->getRepository(Trabajador::class)->findAll();
//      //  $encuentro->setRefEvento($this->nomencladorEncuentro($entityManager));
//        $form = $this->createForm(EncuentroAcuerdoType::class, $acuerdo);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//
//
//            $responsables = $request->request->get('chkgrp');
//            $acuerdo = $form->getData();
//            //$encuentro->setRefEvento($this->nomencladorEncuentro($entityManager));
//            if($responsables !== null){
//                foreach ($responsables as $responsable){
//                    //$acuerdo->addParticipante($entityManager->getRepository(Trabajador::class)->find($participante));
//                }
//            }
//
//
//           //$encuentro->setRefEvento($this->nomencladorEncuentro($entityManager));
//            $entityManager->persist($acuerdo);
//            $entityManager->flush();
//            $this->addFlash("success", "Elemento registrado satisfactoriamente");
//            return $this->redirectToRoute("acuerdo_listar");
//        }
//        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "trabajadores" => $trabajadores]);
//    }

    /**
     * @Route("/nuevos/encuentro={idEncuentro}",name="acuerdo_nuevos")
     */
    public function nuevos(Request $request, EntityManagerInterface $entityManager, $idEncuentro)
    {
        $cant_encuentros = count($entityManager->getRepository(Encuentro::class)->findAll());
        if($idEncuentro === '0' && $cant_encuentros === 0) {
            $this->addFlash("success", "Debe registrar un encuentro. No hay encuentros en el cual registrar acuerdos");
            return $this->redirectToRoute("encuentro_nuevo");
        }
        $acuerdo = new EncuentroAcuerdo();
        $encuentro = $entityManager->getRepository(Encuentro::class)->find($idEncuentro);
        $acuerdos = $entityManager->getRepository(EncuentroAcuerdo::class)->findBy(['encuentro' => $encuentro]);
        if($idEncuentro === false || $encuentro === null){
            $acuerdo->setFechaInicio(new \DateTime());
        }else{
            $acuerdo->setFechaInicio($encuentro->getFechaEvento());
        }
        $form = $this->createForm(EncuentroAcuerdoType::class, $acuerdo);
        $form
            ->add('agregar', SubmitType::class);
        if($idEncuentro === false or $encuentro === null){
            $form->add('encuentro',EntityType::class, [
                'placeholder' => 'Seleccione',
                'mapped' => true,
                'choice_label' => 'refNombreFecha',
                'class' => Encuentro::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.refEvento', 'DESC');
                },
            ]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $acuerdo = $form->getData();
            $personas_array = $request->request->get('personas_acuerdos');
            $trabajadorRepository = $this->getDoctrine()->getRepository(Trabajador::class);

            if(isset($personas_array) and $personas_array !== null){
                foreach ($personas_array as $item){
                    $encuentroAcuerdoPersonaAsociada = new EncuentroAcuerdoPersonaAsociada();

                    $p = explode('***',$item);
                    $pNombreCompleto = $p[0]; $pExterno = $p[1]; $pCargo = $p[2]; $pEntidad = $p[3]; $pResponsable = $p[4];


                    $persona = new Persona();
                    $persona->setNombresApellidos($pNombreCompleto ?: 'N/E');
                    if($pExterno === "true"){
                        $persona = $this->getDoctrine()->getRepository(Persona::class)->findBy(array(
                            'nombresApellidos' => $pNombreCompleto,
                            'cargo' => $pCargo,
                            'entidad' => $pEntidad,
                        ));


                        if(count($persona) === 0){
                            $persona = new Persona();
                            $persona->setCargo(($pCargo !== '-' && $pCargo !== '') ? $pCargo : 'N/E');
                            $persona->setEntidad(($pEntidad !== '-' && $pEntidad !== '') ? $pEntidad : 'N/E');
                            $persona->setNombresApellidos($pNombreCompleto);
                        } else {
                            $persona = $persona[0];
                        }

                    }
                    else{

                        $trabajador = $trabajadorRepository->findByNombreCompleto($pNombreCompleto); // Buscar el trabajador para actualizar la tabla persona
                        if(!empty($trabajador)){
                            $persona = $this->getDoctrine()->getRepository(Persona::class)->findBy(array(
                                'nombresApellidos' => $pNombreCompleto,
                                'cargo' => $trabajador[0]->getCargo()->getNombre(),
                                'entidad' => 'CNSV',
                            ));
                            if(count($persona) === 0){
                                $persona = new Persona();
                                $persona->setCargo($trabajador[0]->getCargo()->getNombre());
                                $persona->setEntidad('CNSV');
                                $persona->setNombresApellidos($pNombreCompleto);
                            } else {
                                $persona = $persona[0];
                            }

                        } else {
                            $persona = $this->getDoctrine()->getRepository(Persona::class)->findBy(array(
                                'nombresApellidos' => $pNombreCompleto,
                                'cargo' => 'N/E',
                                'entidad' => 'CNSV',
                            ));
                            if(count($persona) === 0){
                                $persona = new Persona();
                                $persona->setCargo('N/E');
                                $persona->setEntidad('CNSV');
                                $persona->setNombresApellidos($pNombreCompleto);
                            } else {
                                $persona = $persona[0];
                            }
                        }
                    }
                    $persona->addEncuentroAcuerdoPersonaAsociada($encuentroAcuerdoPersonaAsociada);
                    $entityManager->persist($persona);

                    $acuerdo->addEncuentroAcuerdoPersonaAsociada($encuentroAcuerdoPersonaAsociada);

                    $encuentroAcuerdoPersonaAsociada->setPersona($persona);
                    $encuentroAcuerdoPersonaAsociada->setAcuerdo($acuerdo);

                    $encuentroAcuerdoPersonaAsociada->setResponsable($pResponsable === 'true');
                    $encuentroAcuerdoPersonaAsociada->setPersonaExterna($pExterno === 'true');

                    $entityManager->persist($encuentroAcuerdoPersonaAsociada);

                 }
            }


            $acuerdoEstado = $request->request->get('encuentro_acuerdo')['estado'];
            $observaciones = $request->request->get('encuentro_acuerdo')['observaciones'];
            $acuerdoFechaCumplimiento = isset($request->request->get('encuentro_acuerdo')['fechaCumplimiento']) ? $request->request->get('encuentro_acuerdo')['fechaCumplimiento'] : null ;
            $periodicidad = $acuerdo->getPeriodicidad();
           // $encuentro = $acuerdo->getEncuentro();

            if($encuentro === null) $encuentro = $this->getDoctrine()->getRepository(Encuentro::class)->find($request->request->get('encuentro_acuerdo')['encuentro']);
            switch ($acuerdoEstado){
                case 'En tiempo' : {

                    $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad->setEstado('En tiempo');
                    $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad->setActivo(true); // Los acuerdos en tiempo estan activos siempre
                    $acuerdoTrazabilidad->setEncuentroModificador($encuentro); //representa el encuentro donde se hace cambio de estado
                    $acuerdoTrazabilidad->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
                    $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                    break;}
                case 'Cumplido' : {
                    $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad->setEstado('Cumplido');
                    $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad->setActivo(false);
                    $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                    $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                    $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                    if($periodicidad === 'No'){
                        $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                    } else {
                        $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad2->setEstado('En tiempo');
                        $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad2->setActivo(true);
                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));
                    }
                    break;}
                case 'Cumplido en parte' : {
                    $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad->setEstado('Cumplido en parte');
                    $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad->setActivo(false);
                    $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                    $acuerdoTrazabilidad->setObservaciones('Finalizado');
                    $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                    $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());


                    $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad2->setEstado('En tiempo');
                    $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad2->setActivo(true);
                    //if($periodicidad === 'No'){
                        $acuerdoTrazabilidad2->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
//                    } else {
//                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), new \DateTime($acuerdoFechaCumplimiento)));
//                    }
                    break;}
                case 'Pospuesto' : {
                    $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad->setEstado('Pospuesto');
                    $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad->setActivo(false);
                    $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                    $acuerdoTrazabilidad->setObservaciones('Finalizado');
                    $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                    $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                    $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad2->setEstado('En tiempo');
                    $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad2->setActivo(true);
                 //   if($periodicidad === 'No'){
                        $acuerdoTrazabilidad2->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
//                    } else {
//                        $acuerdoTrazabilidad->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $acuerdoFechaCumplimiento));
//                    }
                    break;}
                case 'Incumplido' : {
                    $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad->setEstado('Incumplido');
                    $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad->setActivo(false);
                    $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                    $acuerdoTrazabilidad->setObservaciones('Finalizado');
                    $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                    $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                    if($periodicidad !== 'No') {
                        $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad2->setEstado('En tiempo');
                        $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad2->setActivo(true);
                        $acuerdoTrazabilidad2->setFechaRevision($acuerdoFechaCumplimiento ? new \DateTime($acuerdoFechaCumplimiento) : $this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));

                    } else{
                        $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                    }
                    break;}
                case 'Cancelado' : {
                    $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad->setEstado('Cancelado');
                    $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad->setActivo(false);
                    $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                    $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                    $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                    $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                    break;}
                case '' : {
                    break;}
            }
            if(isset($acuerdoTrazabilidad)) { $entityManager->persist($acuerdoTrazabilidad); }
            if(isset($acuerdoTrazabilidad2)) { $entityManager->persist($acuerdoTrazabilidad2); }

            if($idEncuentro !== 0) {
                $acuerdo->setEncuentro($encuentro);
            }
            $acuerdo->setNoAcuerdo($this->nomencladorEncuentroAcuerdo($entityManager, $acuerdo->getEncuentro()));
            $acuerdo->setFechaInicio($encuentro->getFechaEvento());
            $entityManager->persist($acuerdo);
            $entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            if($idEncuentro === 0 || $encuentro === null){
                return $this->redirectToRoute("acuerdo_mostrar", ['id' => $acuerdo->getId()]);
            }

            if($form->get('agregar')->isClicked()){
                return $this->redirectToRoute('acuerdo_nuevos', ['idEncuentro' => $acuerdo->getEncuentro()->getId()]);
            }
            return $this->redirectToRoute('acuerdo_listar');
        }
        $trabajadores = $this->getDoctrine()->getRepository(Trabajador::class)->findAll();

        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "acuerdos" => $acuerdos, "encuentro" => $encuentro, "trabajadores" => $trabajadores
        ]);
    }


    private function acuerdoPeriodicidadHandler(string $periodicidad, \DateTime $fecha) : \DateTime{
        $fecha = clone $fecha;
        switch ($periodicidad){
            case 'Mensual': {
                $fecha = date_modify($fecha,"+1 month");
                break;
            }
            case 'Trimestral': {
                $fecha = date_modify($fecha,"+3 month");
                break;
            }
            case 'Semestral': {
                $fecha = date_modify($fecha,"+6 month");
                break;
            }
            case 'Anual': {
                $fecha = date_modify($fecha,"+12 month");
                break;
            }
            default: break;
        }
        if(date("w", $fecha->getTimestamp()) === "0"){
            $fecha = date_modify($fecha,"+1 day");
        }
        if(date("w", $fecha->getTimestamp()) === "6"){
            $fecha = date_modify($fecha,"+2 day");
        }
        return $fecha;
    }

    /**
     * @Route("/editar/{id}",name="acuerdo_editar")
     */
    public function editar(EncuentroAcuerdo $acuerdo, Request $request, EntityManagerInterface $entityManager)
    {
        $idEncuentro = $acuerdo->getEncuentro()->getId();
        $trabajadores = $entityManager->getRepository(Trabajador::class)->findAll();
        $acuerdo_old = clone $acuerdo;

        $options = [
            'estado' => $acuerdo->getUltimoEstado(),
            'fecha_cumplimiento' => $acuerdo->getUltimaFechaCumplimiento(),
            'observaciones' => $acuerdo->getUltimasObservaciones(),
        ];


        $form = $this->createForm(EncuentroAcuerdoType::class, $acuerdo, $options);
        $form
            ->add('agregar'
                , SubmitType::class,['label'=>'Modificar']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $acuerdo = $form->getData();

            $personas_array = $request->request->get('personas_acuerdos');

            $trabajadorRepository = $this->getDoctrine()->getRepository(Trabajador::class);

            if(isset($personas_array) and $personas_array !== null){

                $this->getDoctrine()->getRepository(EncuentroAcuerdoPersonaAsociada::class)->deleteEncuentroAcuerdoPersonasAsociadas($acuerdo);

                foreach ($personas_array as $item){
                    $encuentroAcuerdoPersonaAsociada = new EncuentroAcuerdoPersonaAsociada();

                    $p = explode('***',$item);
                    $pNombreCompleto = $p[0]; $pExterno = $p[1]; $pCargo = $p[2]; $pEntidad = $p[3]; $pResponsable = $p[4];


                    $persona = $this->getDoctrine()->getRepository(Persona::class)->findBy(
                        array(
                            'nombresApellidos' => $pNombreCompleto,
                            'cargo' => $pCargo,
                            'entidad' => $pEntidad,
                        )
                    );

                    if(count($persona) === 0){
                        $persona = new Persona();
                        if($pExterno === "true"){
                            $persona->setCargo(($pCargo !== '-' && $pCargo !== '') ? $pCargo : 'N/E');
                            $persona->setEntidad(($pEntidad !== '-' && $pEntidad !== '') ? $pEntidad : 'N/E');
                            $persona->setNombresApellidos($pNombreCompleto);
                        }
                        else{

                            $trabajador = $trabajadorRepository->findByNombreCompleto($pNombreCompleto); // Buscar el trabajador para actualizar la tabla persona

                            if(!empty($trabajador)){
                                $persona->setCargo($trabajador[0]->getCargo()->getNombre());
                                $persona->setEntidad('CNSV');
                            } else {
                                $persona->setCargo('N/E');
                                $persona->setEntidad('CNSV');
                            }
                        }
                        $persona->setNombresApellidos($pNombreCompleto ?: 'N/E');
                        $entityManager->persist($persona);

                    } else {
                        $persona = $persona[0];
                    }
                    $persona->addEncuentroAcuerdoPersonaAsociada($encuentroAcuerdoPersonaAsociada);
                    $acuerdo->addEncuentroAcuerdoPersonaAsociada($encuentroAcuerdoPersonaAsociada);
                    $encuentroAcuerdoPersonaAsociada->setPersona($persona);
                    $encuentroAcuerdoPersonaAsociada->setAcuerdo($acuerdo);
                    $encuentroAcuerdoPersonaAsociada->setResponsable($pResponsable === 'true');
                    $encuentroAcuerdoPersonaAsociada->setPersonaExterna($pExterno === 'true');
                    $entityManager->persist($encuentroAcuerdoPersonaAsociada);
                }
            }

            $acuerdoEstado = $request->request->get('encuentro_acuerdo')['estado'];
            $observaciones = $request->request->get('encuentro_acuerdo')['observaciones'];
            $acuerdoFechaCumplimiento = isset($request->request->get('encuentro_acuerdo')['fechaCumplimiento']) ? $request->request->get('encuentro_acuerdo')['fechaCumplimiento'] : null ;
            $periodicidad = $acuerdo->getPeriodicidad();
            $encuentro = $acuerdo->getEncuentro();
            if($acuerdoEstado !== $acuerdo->getUltimoEstado()){
                switch ($acuerdoEstado){
                    case 'En tiempo' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('En tiempo');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(true); // Los acuerdos en tiempo estan activos siempre
                        $acuerdoTrazabilidad->setEncuentroModificador($encuentro); //representa el encuentro donde se hace cambio de estado
                        $acuerdoTrazabilidad->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
                        $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                        break;}
                    case 'Cumplido' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Cumplido');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                        $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                        if($periodicidad === 'No'){
                            $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                        } else {
                            $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                            $acuerdoTrazabilidad2->setEstado('En tiempo');
                            $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                            $acuerdoTrazabilidad2->setActivo(true);
                            $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));
                        }
                        break;}
                    case 'Cumplido en parte' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Cumplido en parte');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                        $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());


                        $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad2->setEstado('En tiempo');
                        $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad2->setActivo(true);
                        //if($periodicidad === 'No'){
                        $acuerdoTrazabilidad2->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
//                    } else {
//                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), new \DateTime($acuerdoFechaCumplimiento)));
//                    }
                        break;}
                    case 'Pospuesto' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Pospuesto');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                        $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                        $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad2->setEstado('En tiempo');
                        $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad2->setActivo(true);
                        //   if($periodicidad === 'No'){
                        $acuerdoTrazabilidad2->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
//                    } else {
//                        $acuerdoTrazabilidad->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $acuerdoFechaCumplimiento));
//                    }
                        break;}
                    case 'Incumplido' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Incumplido');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                        $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                        if($periodicidad !== 'No') {
                            $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                            $acuerdoTrazabilidad2->setEstado('En tiempo');
                            $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                            $acuerdoTrazabilidad2->setActivo(true);
                            $acuerdoTrazabilidad2->setFechaRevision($acuerdoFechaCumplimiento ? new \DateTime($acuerdoFechaCumplimiento) : $this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));

                        } else{
                            $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                        }
                        break;}
                    case 'Cancelado' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Cancelado');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                        $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                        $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                        break;}
                    case '' : {
                        break;}
                }
            } else {
                $acuerdoTrazabilidad = $acuerdo->getEncuentroAcuerdoTrazabilidads()[0];
                $acuerdoTrazabilidad->setObservaciones($observaciones);
                $acuerdoTrazabilidad->setFechaRevision(new \DateTime($acuerdoFechaCumplimiento));
            }

            $fecha = $acuerdo->getFechaInicio();
            if(isset($acuerdoTrazabilidad)) { $acuerdoTrazabilidad->setFechaModificacion($fecha); $entityManager->persist($acuerdoTrazabilidad); }
            if(isset($acuerdoTrazabilidad2)) { $acuerdoTrazabilidad2->setObservaciones($acuerdoTrazabilidad2->getEstado()); $acuerdoTrazabilidad2->setFechaModificacion($fecha); $entityManager->persist($acuerdoTrazabilidad2); }

            if($idEncuentro !== 0) {
                $acuerdo->setEncuentro($encuentro);
            }
            $acuerdo->setNoAcuerdo($this->nomencladorEncuentroAcuerdo($entityManager, $acuerdo->getEncuentro()));
            $acuerdo->setFechaInicio($encuentro->getFechaEvento());
            $entityManager->persist($acuerdo);
            $entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
     //       if($idEncuentro === 0 || $encuentro === null){
                return $this->redirectToRoute("acuerdo_mostrar", ['id' => $acuerdo->getId()]);
      //      }

//            if($form->get('agregar')->isClicked()){
//                return $this->redirectToRoute('acuerdo_nuevos', ['idEncuentro' => $acuerdo->getEncuentro()->getId()]);
//            }
        //    return $this->redirectToRoute('acuerdo_listar');
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView(), "trabajadores" => $trabajadores, "acuerdo" => $acuerdo]);
    }

    /**
     * @Route("/eliminar/{id}",name="acuerdo_eliminar")
     */
    public function eliminar(EncuentroAcuerdo $acuerdo, EncuentroAcuerdoRepository $acuerdoRepository)
    {
        $result = $acuerdoRepository->delete($acuerdo);
        /*$this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');*/
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/mostrar/{id}",name="acuerdo_mostrar")
     */
    public function mostrar(EncuentroAcuerdo $encuentroAcuerdo){
        return $this->render($this->rutaTemplates . "show.html.twig", ["acuerdo" => $encuentroAcuerdo]);
    }

    /**
     * @Route("/acciongrupal",name="acuerdo_accion_grupal")
     */
    public function accionGrupal(Request $request, EncuentroAcuerdoRepository $encuentroAcuerdoRepository, BusquedaAvanzadaController $controller)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $acuerdos = $encuentroAcuerdoRepository->findBy(["id" => $ids]);

        if ($action == 'Eliminar') {
            foreach ($acuerdos as $acuerdo) {
              $encuentroAcuerdoRepository->delete($acuerdo);
            }
        } elseif($action == 'Exportar') {
            $phpExcelObject = new Spreadsheet();
            $title = 'Acuerdos';
            $phpExcelObject = $controller->exportarAcuerdosExcel($phpExcelObject, $acuerdos);

            $writer = new Xls($phpExcelObject);
            ob_start();
            $writer->save('php://output');
            $data = ob_get_contents();
            ob_end_clean();

            return $this->json(["message" => "success", "nb" => ["filename" => $title,"href" => "data:application/vnd.ms-excel;base64,".base64_encode($data)]]);


        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($acuerdos)]);
    }

    /**
     * @Route("/cambiar_estado/{id}",name="acuerdo_cambiar_estado", methods={"POST", "GET"})
     */
    public function cambiarEstado(EncuentroAcuerdo $acuerdo, Request $request, EntityManagerInterface $entityManager){
        $nuevoEstado = $request->get("nuevoEstado");
        $observaciones = $request->get("observaciones");
        $nuevaFecha = $request->get("nuevaFecha");
        $encuentro = $entityManager->getRepository(Encuentro::class)->find($request->get("encuentroModificador"));

        $acuerdosTrazabilidads = $entityManager->getRepository(EncuentroAcuerdoTrazabilidad::class)->findBy(array("acuerdo" => $acuerdo, "encuentroModificador" => $encuentro));
        if(count($acuerdosTrazabilidads) === 0) {
            $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
        } else {
            $acuerdoTrazabilidad = $acuerdosTrazabilidads[0];
        }

        switch ($nuevoEstado){
            case 'Cumplido' : {
                $acuerdoTrazabilidad->setEstado('Cumplido');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(false);

                $acuerdo->getEncuentroAcuerdoTrazabilidads()[0]->setActivo(false);

                $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                $periodicidad = $acuerdo->getPeriodicidad();
                if($periodicidad === 'No'){
                    $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                    $acuerdoTrazabilidad->setActivo(false);
                } else {
                    $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad2->setEstado('En tiempo');
                    $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad2->setActivo(true);
                    $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));
                }
                break;}
            case 'Cumplido en parte' : {
                $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad->setEstado('Cumplido en parte');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(false);

                $acuerdo->getEncuentroAcuerdoTrazabilidads()[0]->setActivo(false);

                $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());


                $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad2->setEstado('En tiempo');
                $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad2->setActivo(true);
                //if($periodicidad === 'No'){
                $acuerdoTrazabilidad2->setFechaRevision(new \DateTime($nuevaFecha));
//                    } else {
//                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), new \DateTime($acuerdoFechaCumplimiento)));
//                    }
                break;}
            case 'Pospuesto' : {
                $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad->setEstado('Pospuesto');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(false);

                $acuerdo->getEncuentroAcuerdoTrazabilidads()[0]->setActivo(false);

                $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());

                $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad2->setEstado('En tiempo');
                $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad2->setActivo(true);
                //   if($periodicidad === 'No'){
                $acuerdoTrazabilidad2->setFechaRevision(new \DateTime($nuevaFecha));
//                    } else {
//                        $acuerdoTrazabilidad->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $acuerdoFechaCumplimiento));
//                    }
                break;}
            case 'Incumplido y finalizar' : {
                $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad->setEstado('Incumplido');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(false);

                $acuerdo->getEncuentroAcuerdoTrazabilidads()[0]->setActivo(false);

                $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                $periodicidad = $acuerdo->getPeriodicidad();
                if($periodicidad !== 'No') {
                    $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad2->setEstado('En tiempo');
                    $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad2->setActivo(true);
                    $acuerdoTrazabilidad2->setFechaRevision($nuevaFecha ? new \DateTime($nuevaFecha) : $this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));

                } else{
                    $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                }
                break;}
            case 'Incumplido y replanificar' : {
                $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad->setEstado('Incumplido');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(false);

                $acuerdo->getEncuentroAcuerdoTrazabilidads()[0]->setActivo(false);

                $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());


                    $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                    $acuerdoTrazabilidad2->setEstado('En tiempo');
                    $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                    $acuerdoTrazabilidad2->setActivo(true);
                    $acuerdoTrazabilidad2->setFechaRevision($nuevaFecha ? new \DateTime($nuevaFecha) : $this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $encuentro->getFechaEvento()));


                break;}
            case 'Cancelado' : {
                $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad->setEstado('Cancelado');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(false);

                $acuerdo->getEncuentroAcuerdoTrazabilidads()[0]->setActivo(false);

                $acuerdoTrazabilidad->setEncuentroModificador($encuentro);
                $acuerdoTrazabilidad->setFechaRevision($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setFechaModificacion($encuentro->getFechaEvento());
                $acuerdoTrazabilidad->setObservaciones($observaciones ?: 'Finalizado');
                break;}
            case '' : {
                break;}
        }

        if(isset($acuerdoTrazabilidad)) { $entityManager->persist($acuerdoTrazabilidad); }
        if(isset($acuerdoTrazabilidad2)) { $entityManager->persist($acuerdoTrazabilidad2); }

//        $acuerdoTrazabilidad->setEstado($nuevoEstado);
//        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
//
//            $elems = ['Cumplido', 'Incumplido y finalizar', 'Cancelado'];
//            $acuerdoTrazabilidad->setActivo(!in_array($nuevoEstado, $elems, false));
//
//            $acuerdoTrazabilidad->setFechaModificacion($encuentroModificador->getFechaEvento());
//            $acuerdoTrazabilidad->setEncuentroModificador($encuentroModificador);
//            if ($nuevaFecha != null) {
//                $acuerdoTrazabilidad->setFechaRevision(new \DateTime($nuevaFecha));
//            }
//            $acuerdoTrazabilidad->setObservaciones($observaciones);
//            //
//            $entityManager->persist($acuerdoTrazabilidad);
            $entityManager->flush();
            return $this->json(["message" => "success", "value" => $nuevoEstado]);
    }

    public function nomencladorEncuentroAcuerdo(EntityManagerInterface $entityManager, Encuentro $encuentro){

        $proximo_numero = count($entityManager->getRepository(EncuentroAcuerdo::class)->findAcuerdosByTipoEncuentro($encuentro->getTipoEncuentro())) + 1;
        $consecutivo = '';
        if($proximo_numero < 10)
            $consecutivo .= "000" . $proximo_numero;
        if($proximo_numero >= 10 and $proximo_numero < 100)
            $consecutivo .= "00" . $proximo_numero;
        if($proximo_numero >= 100 and $proximo_numero < 1000)
            $consecutivo .= "0" . $proximo_numero;

        $cadena = implode("-",[
            'AC',
            $encuentro->getTipoEncuentro()->getAbreviatura(),
            $consecutivo,
            date("Y")
        ]);
        return $cadena;
    }



}