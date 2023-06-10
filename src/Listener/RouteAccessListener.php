<?php


namespace App\Listener;


use App\Entity\Trabajador;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;

final class RouteAccessListener
{

    private $entityManager;

    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event){

        $user = $this->security->getUser();
        $em = $this->entityManager;
        $trabajadores = $em->getRepository(Trabajador::class)->findAll();
        dump($event);

    }
}