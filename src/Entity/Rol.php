<?php

namespace App\Entity;

use App\Repository\RolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RolRepository::class)
 */
class Rol
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $identificador;

    /**
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity=SistemaPermiso::class, mappedBy="rol", orphanRemoval=true)
     */
    private $sistemaPermisos;

    /**
     * @ORM\OneToMany(targetEntity=Cargo::class, mappedBy="rolSistema")
     */
    private $cargos;



    public function __construct()
    {
        $this->sistemaPermisos = new ArrayCollection();
        $this->cargos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificador(): ?string
    {
        return $this->identificador;
    }

    public function setIdentificador(string $identificador): self
    {
        $this->identificador = $identificador;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function __toString()
    {
        return $this->descripcion;
    }

    /**
     * @return Collection|SistemaPermiso[]
     */
    public function getSistemaPermisos(): Collection
    {
        return $this->sistemaPermisos;
    }

    public function addSistemaPermiso(SistemaPermiso $sistemaPermiso): self
    {
        if (!$this->sistemaPermisos->contains($sistemaPermiso)) {
            $this->sistemaPermisos[] = $sistemaPermiso;
            $sistemaPermiso->setRol($this);
        }

        return $this;
    }

    public function removeSistemaPermiso(SistemaPermiso $sistemaPermiso): self
    {
        if ($this->sistemaPermisos->removeElement($sistemaPermiso)) {
            // set the owning side to null (unless already changed)
            if ($sistemaPermiso->getRol() === $this) {
                $sistemaPermiso->setRol(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cargo[]
     */
    public function getCargos(): Collection
    {
        return $this->cargos;
    }

    public function addCargo(Cargo $cargo): self
    {
        if (!$this->cargos->contains($cargo)) {
            $this->cargos[] = $cargo;
            $cargo->setRolSistema($this);
        }

        return $this;
    }

    public function removeCargo(Cargo $cargo): self
    {
        if ($this->cargos->removeElement($cargo)) {
            // set the owning side to null (unless already changed)
            if ($cargo->getRolSistema() === $this) {
                $cargo->setRolSistema(null);
            }
        }

        return $this;
    }



}
