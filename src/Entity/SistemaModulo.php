<?php

namespace App\Entity;

use App\Repository\SistemaModuloRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SistemaModuloRepository::class)
 */
class SistemaModulo
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
     * @ORM\OneToMany(targetEntity=SistemaRuta::class, mappedBy="modulo")
     */
    private $sistemaRutas;

    private $mostrar;

    public function __construct()
    {
        $this->sistemaRutas = new ArrayCollection();
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
            $sistemaRuta->setModulo($this);
        }

        return $this;
    }

    public function removeSistemaRuta(SistemaRuta $sistemaRuta): self
    {
        if ($this->sistemaRutas->removeElement($sistemaRuta)) {
            // set the owning side to null (unless already changed)
            if ($sistemaRuta->getModulo() === $this) {
                $sistemaRuta->setModulo(null);
            }
        }

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
}
