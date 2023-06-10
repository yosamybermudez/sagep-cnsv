<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonaRepository;

/**
 * @ORM\Entity(repositoryClass=PersonaRepository::class)
 */
class Persona
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
    private $nombresApellidos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entidad;



    /**
     * @ORM\Column(type="string", length=11, unique=true, nullable=true)
     */
    private $carneIdentidad;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $sexo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nivelEducacional;

    /**
     * @ORM\Column(type="date", nullable=true)
     */

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;


    /**
     * @ORM\OneToMany(targetEntity=EncuentroAcuerdoPersonaAsociada::class, mappedBy="persona")
     */
    private $encuentroAcuerdoPersonaAsociadas;


    public function __construct()
    {
        $this->encuentroAcuerdoPersonaAsociadas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarneIdentidad(): ?string
    {
        return $this->carneIdentidad;
    }

    public function setCarneIdentidad(string $carneIdentidad): self
    {
        $this->carneIdentidad = $carneIdentidad;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(?string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getNivelEducacional(): ?string
    {
        return $this->nivelEducacional;
    }

    public function setNivelEducacional(?string $nivelEducacional): self
    {
        $this->nivelEducacional = $nivelEducacional;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }


    public function getNombresApellidos(): ?string
    {
        return $this->nombresApellidos;
    }

    public function setNombresApellidos(string $nombresApellidos): self
    {
        $this->nombresApellidos = $nombresApellidos;

        return $this;
    }

    public function getEntidad(): ?string
    {
        return $this->entidad;
    }

    public function setEntidad(string $entidad): self
    {
        $this->entidad = $entidad;

        return $this;
    }

    /**
     * @return Collection|EncuentroAcuerdoPersonaAsociada[]
     */
    public function getEncuentroAcuerdoPersonaAsociadas(): Collection
    {
        return $this->encuentroAcuerdoPersonaAsociadas;
    }

    public function addEncuentroAcuerdoPersonaAsociada(EncuentroAcuerdoPersonaAsociada $encuentroAcuerdoPersonaAsociada): self
    {
        if (!$this->encuentroAcuerdoPersonaAsociadas->contains($encuentroAcuerdoPersonaAsociada)) {
            $this->encuentroAcuerdoPersonaAsociadas[] = $encuentroAcuerdoPersonaAsociada;
            $encuentroAcuerdoPersonaAsociada->setPersona($this);
        }

        return $this;
    }

    public function removeEncuentroAcuerdoPersonaAsociada(EncuentroAcuerdoPersonaAsociada $encuentroAcuerdoPersonaAsociada): self
    {
        if ($this->encuentroAcuerdoPersonaAsociadas->removeElement($encuentroAcuerdoPersonaAsociada)) {
            // set the owning side to null (unless already changed)
            if ($encuentroAcuerdoPersonaAsociada->getPersona() === $this) {
                $encuentroAcuerdoPersonaAsociada->setPersona(null);
            }
        }

        return $this;
    }

}
