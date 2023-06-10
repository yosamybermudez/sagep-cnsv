<?php

namespace App\Entity;

use App\Repository\SistemaEntidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SistemaEntidadRepository::class)
 */
class SistemaEntidad
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
     * @ORM\OneToMany(targetEntity=SistemaRuta::class, mappedBy="entidad")
     */
    private $sistemaRutas;

    /**
     * @ORM\OneToMany(targetEntity=SistemaPermiso::class, mappedBy="entidad", orphanRemoval=true)
     */
    private $sistemaPermisos;

    /**
     * @ORM\ManyToMany(targetEntity=Cargo::class, mappedBy="sistemaEntidades")
     */
    private $cargos;

    public function __construct()
    {
        $this->sistemaRutas = new ArrayCollection();
        $this->sistemaPermisos = new ArrayCollection();
        $this->cargos = new ArrayCollection();
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
     * @return Collection|SistemaRuta[]
     */
    public function getSistemaRutas(): Collection
    {
        return $this->sistemaRutas;
    }

    public function addSistemaRuta(SistemaRuta $sistemaRuta): self
    {
        if (!$this->sistemaRutas->contains($sistemaRuta)) {
            $this->sistemaRutas[] = $sistemaRuta;
            $sistemaRuta->setEntidad($this);
        }

        return $this;
    }

    public function removeSistemaRuta(SistemaRuta $sistemaRuta): self
    {
        if ($this->sistemaRutas->removeElement($sistemaRuta)) {
            // set the owning side to null (unless already changed)
            if ($sistemaRuta->getEntidad() === $this) {
                $sistemaRuta->setEntidad(null);
            }
        }

        return $this;
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
            $sistemaPermiso->setEntidad($this);
        }

        return $this;
    }

    public function removeSistemaPermiso(SistemaPermiso $sistemaPermiso): self
    {
        if ($this->sistemaPermisos->removeElement($sistemaPermiso)) {
            // set the owning side to null (unless already changed)
            if ($sistemaPermiso->getEntidad() === $this) {
                $sistemaPermiso->setEntidad(null);
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
            $cargo->addSistemaEntidade($this);
        }

        return $this;
    }

    public function removeCargo(Cargo $cargo): self
    {
        if ($this->cargos->removeElement($cargo)) {
            $cargo->removeSistemaEntidade($this);
        }

        return $this;
    }
}
