<?php

namespace App\Entity;

use App\Repository\TrabajadorRepository;
use AppBundle\Utils\Exception\FKConstraintViolationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrabajadorRepository::class)
 */
class Trabajador
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
    private $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $carneIdentidad;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class, inversedBy="trabajadores")
     */
    private $cargo;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, mappedBy="trabajadorAsociado", cascade={"persist", "remove"})
     */
    private $usuario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $noExpediente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccionCarne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccionResidencia;

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
    private $fechaAlta;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaBaja;

    /**
     * @ORM\ManyToOne(targetEntity=Municipio::class, inversedBy="trabajadores")
     */
    private $municipio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\ManyToMany(targetEntity=Encuentro::class, mappedBy="participantes")
     */
    private $encuentros;

    public function __construct()
    {
        $this->alta = true;
        $this->encuentros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
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

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        // unset the owning side of the relation if necessary
        if ($usuario === null && $this->usuario !== null) {
            $this->usuario->setTrabajadorAsociado(null);
        }

        // set the owning side of the relation if necessary
        if ($usuario !== null && $usuario->getTrabajadorAsociado() !== $this) {
            $usuario->setTrabajadorAsociado($this);
        }

        $this->usuario = $usuario;

        return $this;
    }
	
	public function getNombreCompleto(): ?string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function getAlta(): ?bool
    {
        return $this->alta;
    }

    public function setAlta(bool $alta): self
    {
        $this->alta = $alta;

        return $this;
    }

    public function getNoExpediente(): ?string
    {
        return $this->noExpediente;
    }

    public function setNoExpediente(?string $noExpediente): self
    {
        $this->noExpediente = $noExpediente;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
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

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(?\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    public function getFechaBaja(): ?\DateTimeInterface
    {
        return $this->fechaBaja;
    }

    public function setFechaBaja(?\DateTimeInterface $fechaBaja): self
    {
        $this->fechaBaja = $fechaBaja;

        return $this;
    }

    public function getMunicipio(): ?Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(?Municipio $municipio): self
    {
        $this->municipio = $municipio;

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

    public function getDireccionCarne(): ?string
    {
        return $this->direccionCarne;
    }

    public function setDireccionCarne(?string $direccionCarne): self
    {
        $this->direccionCarne = $direccionCarne;

        return $this;
    }

    public function getDireccionResidencia(): ?string
    {
        return $this->direccionResidencia;
    }

    public function setDireccionResidencia(?string $direccionResidencia): self
    {
        $this->direccionResidencia = $direccionResidencia;

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
            $encuentro->addParticipante($this);
        }

        return $this;
    }

    public function removeEncuentro(Encuentro $encuentro): self
    {
        if ($this->encuentros->removeElement($encuentro)) {
            $encuentro->removeParticipante($this);
        }

        return $this;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove() {
        if ($this->encuentros->count() > 0) {
            throw new FKConstraintViolationException();
        }
   }

}
