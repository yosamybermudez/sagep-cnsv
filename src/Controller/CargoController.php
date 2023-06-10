<?php


namespace App\Controller;


use App\Entity\Cargo;
use App\Entity\Usuario;
use App\Form\CargoType;
use App\Form\UsuarioFormType;
use App\Repository\CargoRepository;
use App\Repository\SistemaPermisoRepository;
use App\Repository\SistemaRutaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/generales/cargo")
 */
class CargoController extends BaseController
{
    private $rutaTemplates = "gestion/cargo/";

    private $entityManager;

    private $cargoRepository;

    public function __construct(SistemaRutaRepository $sistemaRutaRepository, SistemaPermisoRepository $sistemaPermisoRepository, EntityManagerInterface $entityManager, CargoRepository $cargoRepository)
    {
        parent::__construct($sistemaRutaRepository, $sistemaPermisoRepository, $entityManager);
        $this->entityManager = $entityManager;
        $this->cargoRepository = $cargoRepository;
    }

    /**
     * @Route("/",name="cargo_listar", defaults={"area" : "capitalhumano"})
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function listar(CargoRepository $cargoRepository)
    {
        $cargos = $cargoRepository->findAll();
        return $this->render($this->rutaTemplates . "index.html.twig", ["cargos" => $cargos]);
    }

    /**
     * @Route("/nuevo",name="cargo_nuevo")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function nuevo(Request $request)
    {
        $cargo = new Cargo();
        $form = $this->createForm(CargoType::class, $cargo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($cargo);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento registrado satisfactoriamente");
            return $this->redirectToRoute("cargo_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/editar/{id}",name="cargo_editar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function editar(Cargo $cargo, Request $request)
    {
        $form = $this->createForm(CargoType::class, $cargo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $this->entityManager->persist($cargo);
            $this->entityManager->flush();
            $this->addFlash("success", "Elemento modificado satisfactoriamente");
            return $this->redirectToRoute("cargo_listar");
        }
        return $this->render($this->rutaTemplates . "form.html.twig", ["form" => $form->createView()]);
    }

    /**
     * @Route("/eliminar/{id}",name="cargo_eliminar")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function eliminar(Cargo $cargo)
    {
        $result = $this->cargoRepository->delete($cargo);
        return $this->json(["message" => "success", "value" => $result]);
    }

    /**
     * @Route("/acciongrupal",name="cargo_accion_grupal")
     * @IsGranted("ROLE_DIRECTOR_AREA")
     */
    public function accionGrupal(Request $request)
    {
        $action = $request->get("action");
        $ids = $request->get("ids");
        $cargos = $this->cargoRepository->findBy(["id" => $ids]);

        if ($action === 'Eliminar') {
            foreach ($cargos as $cargo) {
                $this->entityManager->remove($cargo);
            }
        } else {
            return $this->json(["message" => "error"]);
        }
        $this->entityManager->flush();
        return $this->json(["message" => "success", "nb" => count($cargos)]);
    }
}