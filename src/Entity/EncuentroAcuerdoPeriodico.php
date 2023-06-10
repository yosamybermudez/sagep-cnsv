<?php

namespace App\Entity;

use App\Repository\EncuentroAcuerdoPeriodicoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncuentroAcuerdoPeriodicoRepository::class)
 */
class EncuentroAcuerdoPeriodico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EncuentroAcuerdo::class, inversedBy="fecha")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acuerdo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaRevision;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

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

    public function getFechaRevision(): ?\DateTimeInterface
    {
        return $this->fechaRevision;
    }

    public function setFechaRevision(\DateTimeInterface $fechaRevision): self
    {
        $this->fechaRevision = $fechaRevision;

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
}
