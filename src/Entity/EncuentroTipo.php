<?php

namespace App\Entity;

use App\Repository\EncuentroTipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncuentroTipoRepository::class)
 */
class EncuentroTipo
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
     * @ORM\Column(type="string", length=255)
     */
    private $abreviatura;

    /**
     * @ORM\OneToMany(targetEntity=Encuentro::class, mappedBy="tipoEncuentro")
     */
    private $encuentros;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    public function __construct()
    {
        $this->encuentros = new ArrayCollection();
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

    public function getAbreviatura(): ?string
    {
        return $this->abreviatura;
    }

    public function setAbreviatura(string $abreviatura): self
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * @return Collection|Encuentro[]
     */
    public function getEncuentros(): Collection
    {
        return $this->encuentros;
    }

    public function addEncuentro(Encuentro $encuentro): self
    {
        if (!$this->encuentros->contains($encuentro)) {
            $this->encuentros[] = $encuentro;
            $encuentro->setTipoEncuentro($this);
        }

        return $this;
    }

    public function removeEncuentro(Encuentro $encuentro): self
    {
        if ($this->encuentros->removeElement($encuentro)) {
            // set the owning side to null (unless already changed)
            if ($encuentro->getTipoEncuentro() === $this) {
                $encuentro->setTipoEncuentro(null);
            }
        }

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
