<?php


namespace App\Controller;


use App\Entity\Provincia;
use App\Entity\Usuario;
use App\Form\ProvinciaType;
use App\Form\UsuarioFormType;
use App\Repository\ProvinciaRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/generales/provincia")
*/
class ProvinciaController extends BaseController
{
    private $rutaTemplates = "generales/provincia/";

    /**
     * @Route("/",name="provincia_listar")
     * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
     */
    public function listar(ProvinciaRepository $provinciaRepository, Request $request)
    {
        $provincias = $provinciaRepository->findBy([],['id' => 'ASC']);
        return $this->render($this->rutaTemplates . "index.html.twig", ["provincias" => $provincias]);
    }

    /**
     * @Route("/nuevo",name="provincia_nuevo")
     * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
     */
    public function nuevo(Request $request)
    {
        $provincia=new Provincia();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProvinciaType::class, $provincia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($provincia);
            $entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("provincia_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="provincia_editar")
     * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
     */
    public function editar(Request $request, Provincia $provincia)
    {
        $form = $this->createForm(ProvinciaType::class, $provincia);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($provincia);
            $entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("provincia_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["userForm" => $form->createView()]);
    }

     /**
     * @Route("/eliminar/{id}",name="provincia_eliminar")
      * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
     */
    public function eliminar(Provincia $provincia, ProvinciaRepository $provinciaRepository)
    {
        $result = $provinciaRepository->delete($provincia);
        return $this->json(["message" => "success", "value" => $result]); // Si result es FALSE muestra un mensaje de error.
    }

    /**
     * @Route("/acciongrupal",name="provincia_accion_grupal")
     * @IsGranted("ROLE_ADMINISTRADOR_SISTEMA")
     */
    public function accionGrupal(Request $request, ProvinciaRepository $provinciaRepository, EntityManagerInterface $entityManager)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $provincias = $provinciaRepository->findBy(["id" => $ids]);

        if ($action == 'Eliminar') {
            foreach ($provincias as $provincia) {
                $provinciaRepository->delete($provincia);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        return $this->json(["message" => "success", "nb" => count($provincias)]);
    }
}