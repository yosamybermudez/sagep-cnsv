<?php


namespace App\Controller;


use App\Entity\Municipio;
use App\Entity\Usuario;
use App\Form\MunicipioType;
use App\Form\UsuarioFormType;
use App\Repository\MunicipioRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/generales/municipio")
 * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
 */
class MunicipioController extends BaseController
{
    private $rutaTemplates = "generales/municipio/";

    /**
     * @Route("/",name="municipio_listar")
     * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
     */
    public function listar(MunicipioRepository $municipioRepository)
    {
        $municipios = $municipioRepository->findBy([],['id' => 'ASC']);
        return $this->render($this->rutaTemplates . "index.html.twig", ["municipios" => $municipios]);
    }

    /**
     * @Route("/nuevo",name="municipio_nuevo")
     */
    public function nuevo(Request $request, EntityManagerInterface $entityManager)
    {
        $municipio = new Municipio();
        $form = $this->createForm(MunicipioType::class, $municipio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($municipio);
            $entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("municipio_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="municipio_editar")
     */
    public function editar(Municipio $municipio, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(MunicipioType::class, $municipio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($municipio);
            $entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("municipio_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/eliminar/{id}",name="municipio_eliminar")
     */
    public function eliminar(Municipio $municipio, MunicipioRepository $municipioRepository)
    {
        $result = $municipioRepository->delete($municipio);
        /*$this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');*/
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/acciongrupal",name="municipio_accion_grupal")
     */
    public function accionGrupal(Request $request, MunicipioRepository $municipioRepository, EntityManagerInterface $entityManager)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $municipios = $municipioRepository->findBy(["id" => $ids]);

        if ($action == 'Eliminar') {
            foreach ($municipios as $municipio) {
              $municipioRepository->delete($municipio);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($municipios)]);
    }
}