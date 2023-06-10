<?php

namespace App\Entity;

use App\Repository\SistemaPermisoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SistemaPermisoRepository::class)
 */
class SistemaPermiso
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Rol::class, inversedBy="sistemaPermisos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rol;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permisoAgregar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $permisoModificar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $permisoEliminar;

    /**
     * @ORM\ManyToOne(targetEntity=SistemaEntidad::class, inversedBy="sistemaPermisos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entidad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $permisoLeer;

    public function __construct()
    {
        $this->permisoAgregar = false;
        $this->permisoLeer = false;
        $this->permisoEliminar = false;
        $this->permisoModificar = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRol(): ?Rol
    {
        return $this->rol;
    }

    public function setRol(?Rol $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getPermisoAgregar(): ?bool
    {
        return $this->permisoAgregar;
    }

    public function setPermisoAgregar(?bool $permisoAgregar): self
    {
        $this->permisoAgregar = $permisoAgregar;

        return $this;
    }

    public function getPermisoModificar(): ?bool
    {
        return $this->permisoModificar;
    }

    public function setPermisoModificar(bool $permisoModificar): self
    {
        $this->permisoModificar = $permisoModificar;

        return $this;
    }

    public function getPermisoEliminar(): ?bool
    {
        return $this->permisoEliminar;
    }

    public function setPermisoEliminar(bool $permisoEliminar): self
    {
        $this->permisoEliminar = $permisoEliminar;

        return $this;
    }

    public function getEntidad(): ?SistemaEntidad
    {
        return $this->entidad;
    }

    public function setEntidad(?SistemaEntidad $entidad): self
    {
        $this->entidad = $entidad;

        return $this;
    }

    public function getPermisoLeer(): ?bool
    {
        return $this->permisoLeer;
    }

    public function setPermisoLeer(bool $permisoLeer): self
    {
        $this->permisoLeer = $permisoLeer;

        return $this;
    }
}
