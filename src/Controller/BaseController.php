<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Comentarios;
use App\Entity\Encuentro;
use App\Entity\FileDocumento;
use App\Entity\Usuario;
use App\Entity\Usuarios;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;



class BaseController extends AbstractController
{
    private $sistemaRutaRepository;
    private $sistemaPermisoRepository;

    public function __construct(SistemaRutaRepository $sistemaRutaRepository, SistemaPermisoRepository $sistemaPermisoRepository, EntityManagerInterface $entityManager)
    {
        $this->sistemaRutaRepository = $sistemaRutaRepository;
        $this->sistemaPermisoRepository = $sistemaPermisoRepository;
    }

    protected function getUser(): Usuario
    {
        return parent::getUser();
    }

    protected function getId()
    {
        return $this->getUser()->getId();
    }

    public function isGrantedRole($permisos)
    {
        foreach ($permisos as $permiso) {
            if ($this->isGranted($permiso->getRol()->getIdentificador())) {
                return true;
            }
        }
        return false;
    }

    public function isGrantedAccessToRoute(Request $request)
    {
        $ruta = $this->sistemaRutaRepository->findOneBy(array('enlace' => $request->get('_route')));
        $permisos = $ruta->getEntidad()->getSistemaPermisos();

        foreach ($permisos as $permiso) {

            if ($this->isGranted($permiso->getRol()->getIdentificador())) {
                return true;
            }
        }
        return false;
    }

    public function comentario($request,$tipo,$obj,$entityManager){
        if($request->request->get($tipo)['comentario']!=''){
            $comentario = new Comentario($this);
            $comentario->setComentario($request->request->get($tipo)['comentario']);
            if ($tipo == 'encuentro'){
                $comentario->setEncuentro($obj);
            }elseif($tipo == 'solicitud_materiales'){
                $comentario->setSolicitudMateriales($obj);
            }
            $entityManager->persist($comentario);
        }
    }

    public function fileDocumento($request,$tipo,$obj,$entityManager){
        if($request->files->get($tipo)['fileDocumento'] !== null && count($request->files->get($tipo)['fileDocumento'])>0){
            $nombre_documento =date("Ymdhis");
            $nombre_real_documento=$request->files->get($tipo)['fileDocumento'][0]->getClientOriginalName();
            //Dirección en la que se guarda el file
            $d = 'assets/fileDocumento/'.$tipo.'/'.date('Y').'/';
            if (!file_exists($d)) {
                mkdir($d, 0755, true);
            }

            $filedocumento = new FileDocumento($this);
            if($tipo == 'encuentro'){
                $filedocumento->setEncuentro($obj);
                $descripcion = $obj->getRefEvento().' - '.$obj->getDescripcion();
            }elseif($tipo == 'solicitud_materiales'){
                $filedocumento->setSolicitudMateriales($obj);
                $descripcion = 'No orden:'.$obj->getNroOrden();
            }
            $filedocumento->setTipo($tipo);
            $filedocumento->setUsuario($this->getDatabaseUser());
            $filedocumento->setFechaSubido(new \DateTime());
            $filedocumento->setUrl('fileDocumento/'.$tipo.'/'.date('Y').'/'.$nombre_documento);
            $filedocumento->setDescripcion($descripcion);
            $filedocumento->setNombreReal($nombre_real_documento);
            if (file_exists($d)) {
                move_uploaded_file($_FILES[$tipo]['tmp_name']['fileDocumento'][0], ($d. $nombre_documento));
            }
            $entityManager->persist($filedocumento);
        }

    }

    public function redirectToReferer(Request $request){
        $referer = $request->get('referer');
        dd($request->get('referer'));
        return $referer !== null ? $this->redirect($referer) : $this->redirectToRoute('homepage');
    }

    public function getDatabaseUser(){
        return $this->getUser();
    }

    public function getDatabaseUserParameter($user){
        return $this->getDoctrine()->getRepository(Usuario::class)->find($user);
    }

    public function pdfBinario(){
        if(PHP_OS=='WINNT') {
            return $this->getParameter('kernel.project_dir').'/config/wkhtmltopdf/bin/wkhtmltopdf.exe';
        }else{
            return $_ENV['WKHTMLTOPDF_PATH'];
        }
    }

}