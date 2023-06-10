<?php

namespace App\Entity;

use App\Repository\EventoSistemaRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=EventoSistemaRepository::class)
 */
class EventoSistema
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="eventos_sistema")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable()
     */
    private $actionDate;

    public function __construct(Usuario $user, string $action)
    {
        $this->user = $user;
        $this->action = $action;
        $this->actionDate = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function getUser(): ?Usuario
    {
        return $this->user;
    }

    public function setUser(?Usuario $user): self
    {
        $this->user = $user;

        return $this;
    }


    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getActionDate(): ?\DateTimeInterface
    {
        return $this->actionDate;
    }

    public function setActionDate(\DateTimeInterface $actionDate): self
    {
        $this->actionDate = $actionDate;

        return $this;
    }

}
