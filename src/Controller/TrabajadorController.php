<?php


namespace App\Controller;


use App\Entity\Cargo;
use App\Entity\Trabajador;
use App\Entity\Usuario;
use App\Form\TrabajadorType;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use App\Repository\TrabajadorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/capitalhumano/trabajadores")
 */
class TrabajadorController extends BaseController
{
    private $rutaTemplates = "capitalhumano/trabajador/";

    private $entityManager;

    public function __construct(SistemaRutaRepository $sistemaRutaRepository, SistemaPermisoRepository $sistemaPermisoRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct($sistemaRutaRepository, $sistemaPermisoRepository, $entityManager);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/",name="trabajador_listar")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function listar(TrabajadorRepository $trabajadorRepository)
    {
        $trabajadores = $trabajadorRepository->findAll();
        return $this->render($this->rutaTemplates . "index.html.twig", ["trabajadores" => $trabajadores]);
    }

    /**
     * @Route("/nuevo",name="trabajador_nuevo")
     * @IsGranted("ROLE_ESPECIALISTA")
     */
    public function nuevo(Request $request)
    {
        $trabajador = new Trabajador();
        $form = $this->createForm(TrabajadorType::class, $trabajador);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($trabajador);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            $redirect = $request->query->get('redirect');
            return $redirect !== null ? $this->redirect($redirect) : $this->redirectToRoute('trabajador_listar');
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="trabajador_editar")
     * @IsGranted("ROLE_ESPECIALISTA_PRINCIPAL")
     */
    public function editar(Request $request, Trabajador $trabajador)
    {
        $form = $this->createForm(TrabajadorType::class, $trabajador);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($trabajador);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("trabajador_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/darbaja/{id}",name="trabajador_dar_baja")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function darBaja(Trabajador $trabajador, TrabajadorRepository $trabajadorRepository)
    {
        $result = $trabajadorRepository->darBaja($trabajador);
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/eliminar/{id}",name="trabajador_eliminar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function eliminar(Trabajador $trabajador, TrabajadorRepository $trabajadorRepository)
    {

        $result = $trabajadorRepository->delete($trabajador);
//        $this->addFlash("success","Utilisateur supprimé");
//        return $this->redirectToRoute('trabajador_listar');
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/acciongrupal",name="trabajador_accion_grupal")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function accionGrupal(Request $request, TranslatorInterface $translator)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $users = $this->userRepository->findBy(["id" => $ids]);

        if ($action == $translator->trans('backend.user.deactivate')) {
            foreach ($users as $user) {
                $user->setValid(false);
                $this->entityManager->persist($user);
            }
        } else if ($action == $translator->trans('backend.user.Activate')) {
            foreach ($users as $user) {
                $user->setValid(true);
                $this->entityManager->persist($user);
            }
        } else if ($action == $translator->trans('backend.user.delete')) {
            foreach ($users as $user) {
                $user->setDeleted(true);
                $this->entityManager->persist($user);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        $this->entityManager->flush();
        return $this->json(["message" => "success", "nb" => count($users)]);
    }
}