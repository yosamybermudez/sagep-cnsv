<?php

namespace App\Entity;

use App\Entity\Encuentro;
use App\Repository\EncuentroAcuerdoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncuentroAcuerdoRepository::class)
 */
class EncuentroAcuerdo
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
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $noAcuerdo;

    /**
     * @ORM\ManyToOne(targetEntity=Encuentro::class, inversedBy="encuentroAcuerdos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $encuentro;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaInicio;

    /**
     * @ORM\ManyToMany(targetEntity=FileDocumento::class, inversedBy="encuentroAcuerdos")
     */
    private $fileDocumentos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $periodicidad;

    /**
     * @ORM\OneToMany(targetEntity=EncuentroAcuerdoPeriodico::class, mappedBy="acuerdo", orphanRemoval=true)
     */
    private $acuerdosPeriodicos;

    /**
     * @ORM\OneToMany(targetEntity=EncuentroAcuerdoTrazabilidad::class, mappedBy="acuerdo", orphanRemoval=true)
     *@ORM\OrderBy({"fechaModificacion" = "DESC", "id" = "DESC"})
     */
    private $encuentroAcuerdoTrazabilidads;


    /**
     * @ORM\OneToMany(targetEntity=EncuentroAcuerdoPersonaAsociada::class, mappedBy="acuerdo")
     */
    private $encuentroAcuerdoPersonaAsociadas;



    public function __construct()
    {
        $this->periodicidad = 'No';
        $this->estado = 'En tiempo';
        $this->acuerdosPeriodicos = new ArrayCollection();
        $this->encuentroAcuerdoTrazabilidads = new ArrayCollection();
        $this->fileDocumentos = new ArrayCollection();
        $this->encuentroAcuerdoPersonaAsociadas = new ArrayCollection();
    }

    public function getUltimasObservaciones(): ?string{
        return $this->getEncuentroAcuerdoTrazabilidads()[0]->getObservaciones();
    }

    public function getUltimoEstado(): ?string{
        return $this->getEncuentroAcuerdoTrazabilidads()[0]->getEstado();
    }

    public function getUltimaFechaCumplimiento(): ?\DateTime{
        return $this->getEncuentroAcuerdoTrazabilidads()[0]->getFechaRevision();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNoAcuerdo(): ?string
    {
        return $this->noAcuerdo;
    }

    public function setNoAcuerdo(?string $noAcuerdo): self
    {
        $this->noAcuerdo = $noAcuerdo;

        return $this;
    }

    public function getEncuentro(): ?Encuentro
    {
        return $this->encuentro;
    }

    public function setEncuentro(?Encuentro $encuentro): self
    {
        $this->encuentro = $encuentro;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): self
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }


    public function getPeriodicidad(): ?string
    {
        return $this->periodicidad;
    }

    public function setPeriodicidad(string $periodicidad): self
    {
        $this->periodicidad = $periodicidad;

        return $this;
    }

    /**
     * @return Collection|EncuentroAcuerdoPeriodico[]
     */
    public function getAcuerdosPeriodicos(): Collection
    {
        return $this->acuerdosPeriodicos;
    }

    public function addAcuerdoPeriodico(EncuentroAcuerdoPeriodico $acuerdoPeriodico): self
    {
        if (!$this->acuerdosPeriodicos->contains($acuerdoPeriodico)) {
            $this->acuerdosPeriodicos[] = $acuerdoPeriodico;
            $acuerdoPeriodico->setAcuerdo($this);
        }

        return $this;
    }

    public function removeAcuerdoPeriodico(EncuentroAcuerdoPeriodico $acuerdoPeriodico): self
    {
        if ($this->acuerdosPeriodicos->removeElement($acuerdoPeriodico)) {
            // set the owning side to null (unless already changed)
            if ($acuerdoPeriodico->getAcuerdo() === $this) {
                $acuerdoPeriodico->setAcuerdo(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|EncuentroAcuerdoTrazabilidad[]
     */
    public function getEncuentroAcuerdoTrazabilidads(): Collection
    {
        return $this->encuentroAcuerdoTrazabilidads;
    }

    public function addEncuentroAcuerdoTrazabilidad(EncuentroAcuerdoTrazabilidad $encuentroAcuerdoTrazabilidad): self
    {
        if (!$this->encuentroAcuerdoTrazabilidads->contains($encuentroAcuerdoTrazabilidad)) {
            $this->encuentroAcuerdoTrazabilidads[] = $encuentroAcuerdoTrazabilidad;
            $encuentroAcuerdoTrazabilidad->setAcuerdo($this);
        }

        return $this;
    }

    public function removeEncuentroAcuerdoTrazabilidad(EncuentroAcuerdoTrazabilidad $encuentroAcuerdoTrazabilidad): self
    {
        if ($this->encuentroAcuerdoTrazabilidads->removeElement($encuentroAcuerdoTrazabilidad)) {
            // set the owning side to null (unless already changed)
            if ($encuentroAcuerdoTrazabilidad->getAcuerdo() === $this) {
                $encuentroAcuerdoTrazabilidad->setAcuerdo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FileDocumento[]
     */
    public function getFileDocumentos(): Collection
    {
        return $this->fileDocumentos;
    }

    public function addFileDocumento(FileDocumento $fileDocumento): self
    {
        if (!$this->fileDocumentos->contains($fileDocumento)) {
            $this->fileDocumentos[] = $fileDocumento;
        }

        return $this;
    }

    public function removeFileDocumento(FileDocumento $fileDocumento): self
    {
        $this->fileDocumentos->removeElement($fileDocumento);

        return $this;
    }

    public function addAcuerdosPeriodico(EncuentroAcuerdoPeriodico $acuerdosPeriodico): self
    {
        if (!$this->acuerdosPeriodicos->contains($acuerdosPeriodico)) {
            $this->acuerdosPeriodicos[] = $acuerdosPeriodico;
            $acuerdosPeriodico->setAcuerdo($this);
        }

        return $this;
    }

    public function removeAcuerdosPeriodico(EncuentroAcuerdoPeriodico $acuerdosPeriodico): self
    {
        if ($this->acuerdosPeriodicos->removeElement($acuerdosPeriodico)) {
            // set the owning side to null (unless already changed)
            if ($acuerdosPeriodico->getAcuerdo() === $this) {
                $acuerdosPeriodico->setAcuerdo(null);
            }
        }

        return $this;
    }

//    public function clearPersonasAsociadas(): self{
//        $this->personasAsociadas->clear();
//
//        return $this;
//    }

    /**
     * @return Collection|EncuentroAcuerdoPersonaAsociada[]
     */
    public function getEncuentroAcuerdoPersonaAsociadas(): Collection
    {
        return $this->encuentroAcuerdoPersonaAsociadas;
    }

    public function getEncuentroAcuerdoPersonaAsociadasToString(): string
    {
        $personas = '';
        $i = 0;
        foreach ($this->getEncuentroAcuerdoPersonaAsociadas() as $item){
            if($i != 0) { $personas .= ' / '; }
            $persona = $item->getPersona();
            if($item->getResponsable()) { $personas .= ' (R) '; }
            $personas .= $persona->getNombresApellidos() . " (" . $persona->getEntidad() . " - " . $persona->getCargo() . ")";
            $i++;
        }
        return $personas;
    }

    public function addEncuentroAcuerdoPersonaAsociada(EncuentroAcuerdoPersonaAsociada $encuentroAcuerdoPersonaAsociada): self
    {
        if (!$this->encuentroAcuerdoPersonaAsociadas->contains($encuentroAcuerdoPersonaAsociada)) {
            $this->encuentroAcuerdoPersonaAsociadas[] = $encuentroAcuerdoPersonaAsociada;
            $encuentroAcuerdoPersonaAsociada->setAcuerdo($this);
        }

        return $this;
    }

    public function removeEncuentroAcuerdoPersonaAsociada(EncuentroAcuerdoPersonaAsociada $encuentroAcuerdoPersonaAsociada): self
    {
        if ($this->encuentroAcuerdoPersonaAsociadas->removeElement($encuentroAcuerdoPersonaAsociada)) {
            // set the owning side to null (unless already changed)
            if ($encuentroAcuerdoPersonaAsociada->getAcuerdo() === $this) {
                $encuentroAcuerdoPersonaAsociada->setAcuerdo(null);
            }
        }

        return $this;
    }

    public function clearPersonasAsociadas(): self {
        $this->encuentroAcuerdoPersonaAsociadas->clear();

        return $this;
    }

}
