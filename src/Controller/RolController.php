<?php

namespace App\Controller;

use App\Repository\RolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/configuracion/rol")
 */
class RolController extends BaseController
{
    private $rutaTemplates = "config/rol/";

    /**
     * @Route("/", name="rol_listar")
     */
    public function listar(RolRepository $rolRepository): Response
    {
        return $this->render( $this->rutaTemplates . 'index.html.twig', [
            'roles' => $rolRepository->findAll()
        ]);
    }
}
