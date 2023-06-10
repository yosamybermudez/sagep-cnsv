<?php

namespace App\Entity;

use App\Repository\SistemaRutaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SistemaRutaRepository::class)
 */
class SistemaRuta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $icono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enlace;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $parametros;

    /**
     * @ORM\ManyToOne(targetEntity=SistemaModulo::class, inversedBy="sistemaRutas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modulo;


    /**
     * @ORM\ManyToOne(targetEntity=SistemaEntidad::class, inversedBy="sistemaRutas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $entidad;


    private $mostrar;

    /**
     * @ORM\ManyToMany(targetEntity=Rol::class)
     */
    private $roles;


    public function __construct()
    {
        $this->sistemaPermisos = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIcono(): ?string
    {
        return $this->icono;
    }

    public function setIcono(string $icono): self
    {
        $this->icono = $icono;

        return $this;
    }

    public function getEnlace(): ?string
    {
        return $this->enlace;
    }

    public function setEnlace(?string $enlace): self
    {
        $this->enlace = $enlace;

        return $this;
    }

    public function getModulo(): ?SistemaModulo
    {
        return $this->modulo;
    }

    public function setModulo(?SistemaModulo $modulo): self
    {
        $this->modulo = $modulo;

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

    public function getMostrar(): ?bool
    {
        return $this->mostrar;
    }

    public function setMostrar(?bool $mostrar): self
    {
        $this->mostrar = $mostrar;

        return $this;
    }

    /**
     * @return Collection|Rol[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Rol $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Rol $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    public function getParametros(): ?string
    {
        return $this->parametros;
    }

    public function setParametros(?string $parametros): self
    {
        $this->parametros = $parametros;

        return $this;
    }
}
