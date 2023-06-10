<?php

namespace App\Entity;

use App\Repository\MunicipioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MunicipioRepository::class)
 */
class Municipio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity=Provincia::class, inversedBy="municipios")
     */
    private $provincia;

    /**
     * @ORM\OneToMany(targetEntity=Trabajador::class, mappedBy="municipio")
     */
    private $trabajadores;

    public function __construct()
    {
        $this->trabajadores = new ArrayCollection();
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
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

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincia $provincia): self
    {
        $this->provincia = $provincia;

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
            $trabajadore->setMunicipio($this);
        }

        return $this;
    }

    public function removeTrabajadore(Trabajador $trabajadore): self
    {
        if ($this->trabajadores->removeElement($trabajadore)) {
            // set the owning side to null (unless already changed)
            if ($trabajadore->getMunicipio() === $this) {
                $trabajadore->setMunicipio(null);
            }
        }

        return $this;
    }
}
