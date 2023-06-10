<?php

namespace App\Entity;

use App\Repository\EncuentroAcuerdoPersonaAsociadaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncuentroAcuerdoPersonaAsociadaRepository::class)
 */
class EncuentroAcuerdoPersonaAsociada
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EncuentroAcuerdo::class, inversedBy="encuentroAcuerdoPersonaAsociadas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acuerdo;

    /**
     * @ORM\ManyToOne(targetEntity=Persona::class, inversedBy="encuentroAcuerdoPersonaAsociadas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $persona;

    /**
     * @ORM\Column(type="boolean")
     */
    private $responsable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $personaExterna;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcuerdo(): ?EncuentroAcuerdo
    {
        return $this->acuerdo;
    }

    public function setAcuerdo(?EncuentroAcuerdo $acuerdo): self
    {
        $this->acuerdo = $acuerdo;

        return $this;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    public function getResponsable(): ?bool
    {
        return $this->responsable;
    }

    public function setResponsable(bool $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getPersonaExterna(): ?bool
    {
        return $this->personaExterna;
    }

    public function setPersonaExterna(bool $personaExterna): self
    {
        $this->personaExterna = $personaExterna;

        return $this;
    }
}
