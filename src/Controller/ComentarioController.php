<?php


namespace App\Controller;


use App\Entity\Comentario;
use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Entity\FileDocumento;
use App\Entity\Municipio;
use App\Entity\Trabajador;
use App\Entity\Usuario;
use App\Form\EncuentroType;
use App\Form\MunicipioType;
use App\Form\UsuarioFormType;
use App\Repository\ComentarioRepository;
use App\Repository\EncuentroRepository;
use App\Repository\MunicipioRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/comentario")
 */
class ComentarioController extends BaseController
{
    private $rutaTemplates = "comentario/";

    /**
     * @Route("/",name="comentario_listar")
     */
    public function listar(ComentarioRepository $comentarioRepository)
    {
        $comentarios = $comentarioRepository->findBy([],['fechaCreado' => 'DESC']);
        return $this->render($this->rutaTemplates . "index.html.twig", ["comentarios" => $comentarios]);
    }

    /**
     * @Route("/nuevo?entidad={entidad}&id={id}",name="comentario_nuevo", methods={"POST"})
     */
    public function nuevo(Request $request, EntityManagerInterface $entityManager, string $entidad, int $id)
    {
        $comentario = new Comentario($this);
        $comentario->setComentario($request->request->get("comentario"));
        switch ($entidad){
            case 'encuentro': {
                $encuentro = $entityManager->getRepository(Encuentro::class)->find($id);
                $comentario->setEncuentro($encuentro);
            }
        }
        $entityManager->persist($comentario);
        $entityManager->flush();
        return $this->json(["message" => "success", "value" => $comentario->getId()]);
    }

    /**
     * @Route("/editar/{id}",name="comentario_editar")
     */
    public function editar(Comentario $comentario, Request $request, EntityManagerInterface $entityManager)
    {
        $comentario->setComentario($request->request->get("comentario"));
        $comentario->setFechaModificado(new \DateTime());
        $comentario->setUsuarioUltimaModificacion($this->getDatabaseUser());
        $entityManager->persist($comentario);
        $entityManager->flush();
        return $this->json(["message" => "success", "value" => $comentario->getId()]);
    }

    /**
     * @Route("/responder/{id}",name="comentario_responder")
     */
    public function responder(Comentario $comentario, Request $request, EntityManagerInterface $entityManager)
    {
        $respuesta = clone $comentario;
        $respuesta->setComentario($request->request->get("comentario"));
        $respuesta->setComentarioPadre($comentario);
        $respuesta->setUsuario($this->getDatabaseUser());
        $respuesta->setUsuarioUltimaModificacion($this->getDatabaseUser());
        $respuesta->setFechaCreado(new \DateTime());
        $respuesta->setFechaModificado(new \DateTime());
        $entityManager->persist($respuesta);
        $entityManager->flush();
        return $this->json(["message" => "success", "value" => $respuesta->getId()]);
    }

    /**
     * @Route("/eliminar/{id}",name="comentario_eliminar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function eliminar(Comentario $comentario, ComentarioRepository $comentarioRepository)
    {
        $result = $comentarioRepository->delete($comentario);
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/mostrar/{id}",name="comentario_mostrar")
     */
    public function mostrar(Encuentro $encuentro){
        return $this->render($this->rutaTemplates . "show.html.twig", ["encuentro" => $encuentro]);
    }

    /**
     * @Route("/acciongrupal",name="comentario_accion_grupal")
     */
    public function accionGrupal(Request $request, ComentarioRepository $comentarioRepository)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $comentarios = $comentarioRepository->findBy(["id" => $ids]);

        if ($action == 'Eliminar') {
            foreach ($comentarios as $comentario) {
              $comentarioRepository->delete($comentario);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($comentarios)]);
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