<?php

namespace App\Entity;

use App\Repository\EncuentroAcuerdoTrazabilidadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncuentroAcuerdoTrazabilidadRepository::class)
 */
class EncuentroAcuerdoTrazabilidad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EncuentroAcuerdo::class, inversedBy="encuentroAcuerdoTrazabilidads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acuerdo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Encuentro::class, inversedBy="encuentroAcuerdoTrazabilidads")
     * @ORM\JoinColumn(nullable=true)
     */
    private $encuentroModificador;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaModificacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRevision;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getEncuentroModificador(): ?Encuentro
    {
        return $this->encuentroModificador;
    }

    public function setEncuentroModificador(?Encuentro $encuentroModificador): self
    {
        $this->encuentroModificador = $encuentroModificador;

        return $this;
    }

    public function getFechaModificacion(): ?\DateTimeInterface
    {
        return $this->fechaModificacion;
    }

    public function setFechaModificacion(\DateTimeInterface $fechaModificacion): self
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

    public function getFechaRevision(): ?\DateTimeInterface
    {
        return $this->fechaRevision;
    }

    public function setFechaRevision(?\DateTimeInterface $fechaRevision): self
    {
        $this->fechaRevision = $fechaRevision;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }
}
