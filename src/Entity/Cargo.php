<?php

namespace App\Entity;

use App\Repository\CargoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CargoRepository::class)
 */
class Cargo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Trabajador::class, mappedBy="cargo")
     */
    private $trabajadores;

    /**
     * @ORM\ManyToMany(targetEntity=SistemaEntidad::class, inversedBy="cargos")
     */
    private $sistemaEntidades;

    /**
     * @ORM\ManyToOne(targetEntity=Rol::class, inversedBy="cargos")
     */
    private $rolSistema;

    public function __construct()
    {
        $this->trabajadores = new ArrayCollection();
        $this->sistemaEntidades = new ArrayCollection();
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

    /**
     * @return Collection|Trabajador[]
     */
    public function getTrabajadores(): Collection
    {
        return $this->trabajadores;
    }

    public function addTrabajadore(Trabajador $trabajadore): self
    {
        if (!$this->trabajadores->contains($trabajadore)) {
            $this->trabajadores[] = $trabajadore;
            $trabajadore->setCargo($this);
        }

        return $this;
    }

    public function removeTrabajadore(Trabajador $trabajadore): self
    {
        if ($this->trabajadores->removeElement($trabajadore)) {
            // set the owning side to null (unless already changed)
            if ($trabajadore->getCargo() === $this) {
                $trabajadore->setCargo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SistemaEntidad[]
     */
    public function getSistemaEntidades(): Collection
    {
        return $this->sistemaEntidades;
    }

    public function addSistemaEntidade(SistemaEntidad $sistemaEntidade): self
    {
        if (!$this->sistemaEntidades->contains($sistemaEntidade)) {
            $this->sistemaEntidades[] = $sistemaEntidade;
        }

        return $this;
    }

    public function removeSistemaEntidade(SistemaEntidad $sistemaEntidade): self
    {
        $this->sistemaEntidades->removeElement($sistemaEntidade);

        return $this;
    }

    public function getRolSistema(): ?Rol
    {
        return $this->rolSistema;
    }

    public function setRolSistema(?Rol $rolSistema): self
    {
        $this->rolSistema = $rolSistema;

        return $this;
    }
}
