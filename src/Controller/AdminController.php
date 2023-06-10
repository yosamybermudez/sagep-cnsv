<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin",name="admin_index")
     */
    public function index(){
        return $this->render("admin/main.html.twig");
    }

    /**
     * @Route("/admin",name="admin_changepswd")
     */
    public function changePassword(){
        return $this->render("admin/main.html.twig");
    }

}
