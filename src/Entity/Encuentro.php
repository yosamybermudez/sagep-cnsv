<?php

namespace App\Entity;

use App\Repository\EncuentroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=EncuentroRepository::class)
 */
class Encuentro
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_evento", type="datetime", nullable=true)
     */
    private $fechaEvento;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", nullable=true)
     */
    private $descripcion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ref_evento", type="string", nullable=true, unique=true)
     */
    private $refEvento;

    /**
     * @var string|null
     *
     * @ORM\Column(name="estado", type="string", nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $invitados = [];

    /**
     * @var string|null
     *
     * @ORM\Column(name="motivo_cancelacion", type="string", nullable=true)
     */
    private $motivoCancelacion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lugar", type="string", nullable=true)
     */
    private $lugar;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dirige_encuentro", type="string", nullable=true)
     */
    private $dirigeEncuentro;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="hora_fin", type="time", nullable=true)
     */
    private $horaFin;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cantidad_trabajadores", type="integer", nullable=true)
     */
    private $cantidadTrabajadores;

    /**
     * @var string|null
     *
     * @ORM\Column(name="documentos_anexos", type="string", nullable=true)
     */
    private $documentosAnexos;

    /**
     * @ORM\OneToMany(targetEntity=EncuentroAcuerdo::class, mappedBy="encuentro", orphanRemoval=true)
     */
    private $encuentroAcuerdos;

    /**
     * @ORM\ManyToOne(targetEntity=EncuentroTipo::class, inversedBy="encuentros")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoEncuentro;

    /**
     * @ORM\ManyToMany(targetEntity=Trabajador::class, inversedBy="encuentros")
     */
    private $participantes;

    /**
     * @ORM\OneToMany(targetEntity=FileDocumento::class, mappedBy="encuentro")
     */
    private $fileDocumentos;

    /**
     * @ORM\OneToMany(targetEntity=Comentario::class, mappedBy="encuentro")
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity=EncuentroAcuerdoTrazabilidad::class, mappedBy="encuentroModificador")
     */
    private $encuentroAcuerdoTrazabilidads;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fechaEvento = new \DateTime();
        $this->hora = new \DateTime();
        $this->encuentroAcuerdos = new ArrayCollection();
        $this->participantes = new ArrayCollection();
        $this->fileDocumentos = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->encuentroAcuerdoTrazabilidads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaEvento(): ?\DateTimeInterface
    {
        return $this->fechaEvento;
    }

    public function setFechaEvento(?\DateTimeInterface $fechaEvento): self
    {
        $this->fechaEvento = $fechaEvento;

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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getRefEvento(): ?string
    {
        return $this->refEvento;
    }

    public function setRefEvento(?string $refEvento): self
    {
        $this->refEvento = $refEvento;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getMotivoCancelacion(): ?string
    {
        return $this->motivoCancelacion;
    }

    public function setMotivoCancelacion(?string $motivoCancelacion): self
    {
        $this->motivoCancelacion = $motivoCancelacion;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(?\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getLugar(): ?string
    {
        return $this->lugar;
    }

    public function setLugar(?string $lugar): self
    {
        $this->lugar = $lugar;

        return $this;
    }

    public function getDirigeEncuentro(): ?string
    {
        return $this->dirigeEncuentro;
    }

    public function setDirigeEncuentro(?string $dirigeEncuentro): self
    {
        $this->dirigeEncuentro = $dirigeEncuentro;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->horaFin;
    }

    public function setHoraFin(?\DateTimeInterface $horaFin): self
    {
        $this->horaFin = $horaFin;

        return $this;
    }

        public function getDocumentosAnexos(): ?string
    {
        return $this->documentosAnexos;
    }

    public function setDocumentosAnexos(?string $documentosAnexos): self
    {
        $this->documentosAnexos = $documentosAnexos;

        return $this;
    }

    /**
     * @return Collection|EncuentroAcuerdo[]
     */
    public function getEncuentroAcuerdos(): Collection
    {
        return $this->encuentroAcuerdos;
    }

    public function addEncuentroAcuerdo(EncuentroAcuerdo $encuentroAcuerdo): self
    {
        if (!$this->encuentroAcuerdos->contains($encuentroAcuerdo)) {
            $this->encuentroAcuerdos[] = $encuentroAcuerdo;
            $encuentroAcuerdo->setEncuentro($this);
        }

        return $this;
    }

    public function removeEncuentroAcuerdo(EncuentroAcuerdo $encuentroAcuerdo): self
    {
        if ($this->encuentroAcuerdos->removeElement($encuentroAcuerdo)) {
            // set the owning side to null (unless already changed)
            if ($encuentroAcuerdo->getEncuentro() === $this) {
                $encuentroAcuerdo->setEncuentro(null);
            }
        }

        return $this;
    }

    public function getCantidadTrabajadores(): ?int
    {
        return $this->cantidadTrabajadores;
    }

    public function setCantidadTrabajadores(?int $cantidadTrabajadores): self
    {
        $this->cantidadTrabajadores = $cantidadTrabajadores;

        return $this;
    }

    public function getTipoEncuentro(): ?EncuentroTipo
    {
        return $this->tipoEncuentro;
    }

    public function setTipoEncuentro(?EncuentroTipo $tipoEncuentro): self
    {
        $this->tipoEncuentro = $tipoEncuentro;

        return $this;
    }

    /**
     * @return Collection|Trabajador[]
     */
    public function getParticipantes(): Collection
    {
        return $this->participantes;
    }

    public function addParticipante(Trabajador $participante): self
    {
        if (!$this->participantes->contains($participante)) {
            $this->participantes[] = $participante;
        }

        return $this;
    }

    public function removeParticipante(Trabajador $participante): self
    {
        $this->participantes->removeElement($participante);

        return $this;
    }

    public function getRefDescripcion(): ?string
    {
        return $this->refEvento.'-'.$this->descripcion;
    }

    public function getRefNombreFecha(): ?string
    {
        return '(' . $this->getFechaEvento()->format('d-m-Y') . ') ' . $this->getRefEvento() . ' - ' .$this->getNombre();
    }

    public function getInvitados(): ?array
    {
        return $this->invitados;
    }

    public function setInvitados(array $invitados): self
    {
        $this->invitados = $invitados;

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
            $fileDocumento->setEncuentro($this);
        }

        return $this;
    }

    public function removeFileDocumento(FileDocumento $fileDocumento): self
    {
        if ($this->fileDocumentos->removeElement($fileDocumento)) {
            // set the owning side to null (unless already changed)
            if ($fileDocumento->getEncuentro() === $this) {
                $fileDocumento->setEncuentro(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comentario[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios[] = $comentario;
            $comentario->setEncuentro($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getEncuentro() === $this) {
                $comentario->setEncuentro(null);
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
            $encuentroAcuerdoTrazabilidad->setEncuentroModificador($this);
        }

        return $this;
    }

    public function removeEncuentroAcuerdoTrazabilidad(EncuentroAcuerdoTrazabilidad $encuentroAcuerdoTrazabilidad): self
    {
        if ($this->encuentroAcuerdoTrazabilidads->removeElement($encuentroAcuerdoTrazabilidad)) {
            // set the owning side to null (unless already changed)
            if ($encuentroAcuerdoTrazabilidad->getEncuentroModificador() === $this) {
                $encuentroAcuerdoTrazabilidad->setEncuentroModificador(null);
            }
        }

        return $this;
    }



}
