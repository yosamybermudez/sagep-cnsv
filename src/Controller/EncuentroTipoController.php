<?php


namespace App\Controller;


use App\Entity\Encuentro;
use App\Entity\EncuentroTipo;
use App\Entity\Trabajador;
use App\Form\EncuentroTipoType;
use App\Form\EncuentroType;
use App\Repository\EncuentroRepository;
use App\Repository\EncuentroTipoRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gestion/tipo_encuentro")
 */
class EncuentroTipoController extends BaseController
{
    private $rutaTemplates = "gestion/encuentro_tipo/";

    /**
     * @Route("/",name="encuentro_tipo_listar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function listar(EncuentroTipoRepository $encuentroTipoRepository)
    {
        $encuentroTipos = $encuentroTipoRepository->findAll();

        return $this->render($this->rutaTemplates . "index.html.twig", ["encuentroTipos" => $encuentroTipos]);
    }

    /**
     * @Route("/nuevo",name="encuentro_tipo_nuevo")
     */
    public function nuevo(Request $request, EntityManagerInterface $entityManager)
    {
        $tipo = new EncuentroTipo();
        $form = $this->createForm(EncuentroTipoType::class, $tipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            do{
                try{
                    $entityManager->persist($tipo);
                    $entityManager->flush();
                    break;
                } catch (UniqueConstraintViolationException $e){
                    throw new \Exception($e->getMessage());
                }
            } while(true);

            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("encuentro_tipo_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="encuentro_tipo_editar")
     */
    public function editar(EncuentroTipo $tipo, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(EncuentroTipoType::class, $tipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            do{
                try{
                    $entityManager->persist($tipo);
                    $entityManager->flush();
                    break;
                } catch (UniqueConstraintViolationException $e){
                    throw new \Exception($e->getMessage());
                }
            } while(true);

            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("encuentro_tipo_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/eliminar/{id}",name="encuentro_tipo_eliminar")
     */
    public function eliminar(EncuentroTipo $tipo, EncuentroTipoRepository $encuentroTipoRepository)
    {
        $result = $encuentroTipoRepository->delete($tipo);
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/acciongrupal",name="encuentro_tipo_accion_grupal")
     */
    public function accionGrupal(Request $request, EncuentroTipoRepository $encuentroTipoRepository)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $tipos = $encuentroTipoRepository->findBy(["id" => $ids]);

        // Si se seleccionan dos tipos de encuentro y se da eliminar. Si alguno de ellos no se puede eliminar. No se puede realizar la eliminación

        // Solucion: debe eliminar todos los seleccionados excepto los que den error.

        if ($action == 'Eliminar') {
            foreach ($tipos as $tipo) {
              $encuentroTipoRepository->delete($tipo);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($tipos)]);
    }
}