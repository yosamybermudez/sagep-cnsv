<?php

namespace App\Entity;

use App\Repository\SolicitudMaterialesRepository;
use App\Controller\BaseController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SolicitudMateriales
 *
/**
 * @ORM\Entity(repositoryClass=SolicitudMaterialesRepository::class)
 */
class SolicitudMateriales
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\SolicitudMaterialesProductos", mappedBy="solicitudMateriales")
     */
    private $solicitudesMaterialesProductos;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $organismo;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $empresa;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $unidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $codigoUnidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $almacen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nroOrden;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $centroCosto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigoCentroCosto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nroLote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $producto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $otros;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $solicitadoPorCargo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $solicitadoPorNombreCompleto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $solicitadoPorFecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recibidoPorCargo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recibidoPorNombreCompleto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $recibidoPorFecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nroSolicitud;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $valeEntrega;


    /**
     * @ORM\OneToMany(targetEntity=FileDocumento::class, mappedBy="solicitudMateriales")
     */
    private $fileDocumentos;

    /**
     * @ORM\OneToMany(targetEntity=Comentario::class, mappedBy="solicitudMateriales")
     */
    private $comentarios;

    /**
     * Constructor
     */
    public function __construct(BaseController $controller)
    {
        $this->solicitadoPorFecha = new \DateTime();
        $this->solicitudesMaterialesProductos = new ArrayCollection();
        $this->fileDocumentos = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function blankSolicitudesMaterialesProducto() : self {
//        $solicitudes = $this->getSolicitudesMaterialesProductos();
//        foreach ($solicitudes as $sol){
//            $this->removeSolicitudesMaterialesProducto($sol);
//        }
        $this->solicitudesMaterialesProductos = new ArrayCollection();
        return $this;
    }

    public function getOrganismo(): ?string
    {
        return $this->organismo;
    }

    public function setOrganismo(string $organismo): self
    {
        $this->organismo = $organismo;

        return $this;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getUnidad(): ?string
    {
        return $this->unidad;
    }

    public function setUnidad(string $unidad): self
    {
        $this->unidad = $unidad;

        return $this;
    }

    public function getCodigoUnidad(): ?string
    {
        return $this->codigoUnidad;
    }

    public function setCodigoUnidad(string $codigoUnidad): self
    {
        $this->codigoUnidad = $codigoUnidad;

        return $this;
    }

    public function getAlmacen(): ?string
    {
        return $this->almacen;
    }

    public function setAlmacen(string $almacen): self
    {
        $this->almacen = $almacen;

        return $this;
    }

    public function getNroOrden(): ?string
    {
        return $this->nroOrden;
    }

    public function setNroOrden(?string $nroOrden): self
    {
        $this->nroOrden = $nroOrden;

        return $this;
    }

    public function getCentroCosto(): ?string
    {
        return $this->centroCosto;
    }

    public function setCentroCosto(string $centroCosto): self
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }

    public function getCodigoCentroCosto(): ?string
    {
        return $this->codigoCentroCosto;
    }

    public function setCodigoCentroCosto(string $codigoCentroCosto): self
    {
        $this->codigoCentroCosto = $codigoCentroCosto;

        return $this;
    }

    public function getNroLote(): ?string
    {
        return $this->nroLote;
    }

    public function setNroLote(string $nroLote): self
    {
        $this->nroLote = $nroLote;

        return $this;
    }

    public function getProducto(): ?string
    {
        return $this->producto;
    }

    public function setProducto(string $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getOtros(): ?string
    {
        return $this->otros;
    }

    public function setOtros(?string $otros): self
    {
        $this->otros = $otros;

        return $this;
    }

    public function getSolicitadoPorCargo(): ?string
    {
        return $this->solicitadoPorCargo;
    }

    public function setSolicitadoPorCargo(string $solicitadoPorCargo): self
    {
        $this->solicitadoPorCargo = $solicitadoPorCargo;

        return $this;
    }

    public function getSolicitadoPorNombreCompleto(): ?string
    {
        return $this->solicitadoPorNombreCompleto;
    }

    public function setSolicitadoPorNombreCompleto(string $solicitadoPorNombreCompleto): self
    {
        $this->solicitadoPorNombreCompleto = $solicitadoPorNombreCompleto;

        return $this;
    }

    public function getSolicitadoPorFecha(): ?\DateTimeInterface
    {
        return $this->solicitadoPorFecha;
    }

    public function setSolicitadoPorFecha(?\DateTimeInterface $solicitadoPorFecha): self
    {
        $this->solicitadoPorFecha = $solicitadoPorFecha;

        return $this;
    }

    public function getRecibidoPorCargo(): ?string
    {
        return $this->recibidoPorCargo;
    }

    public function setRecibidoPorCargo(string $recibidoPorCargo): self
    {
        $this->recibidoPorCargo = $recibidoPorCargo;

        return $this;
    }

    public function getRecibidoPorNombreCompleto(): ?string
    {
        return $this->recibidoPorNombreCompleto;
    }

    public function setRecibidoPorNombreCompleto(string $recibidoPorNombreCompleto): self
    {
        $this->recibidoPorNombreCompleto = $recibidoPorNombreCompleto;

        return $this;
    }

    public function getRecibidoPorFecha(): ?\DateTimeInterface
    {
        return $this->recibidoPorFecha;
    }

    public function setRecibidoPorFecha(?\DateTimeInterface $recibidoPorFecha): self
    {
        $this->recibidoPorFecha = $recibidoPorFecha;

        return $this;
    }

    public function getNroSolicitud(): ?string
    {
        return $this->nroSolicitud;
    }

    public function setNroSolicitud(string $nroSolicitud): self
    {
        $this->nroSolicitud = $nroSolicitud;

        return $this;
    }

    public function getValeEntrega(): ?string
    {
        return $this->valeEntrega;
    }

    public function setValeEntrega(string $valeEntrega): self
    {
        $this->valeEntrega = $valeEntrega;

        return $this;
    }

    /**
     * @return Collection|SolicitudMaterialesProductos[]
     */
    public function getSolicitudesMaterialesProductos(): Collection
    {
        return $this->solicitudesMaterialesProductos;
    }

    public function addSolicitudesMaterialesProducto(SolicitudMaterialesProductos $solicitudesMaterialesProducto): self
    {
        if (!$this->solicitudesMaterialesProductos->contains($solicitudesMaterialesProducto)) {
            $this->solicitudesMaterialesProductos[] = $solicitudesMaterialesProducto;
            $solicitudesMaterialesProducto->setSolicitudMateriales($this);
        }

        return $this;
    }

    public function removeSolicitudesMaterialesProducto(SolicitudMaterialesProductos $solicitudesMaterialesProducto): self
    {
        if ($this->solicitudesMaterialesProductos->removeElement($solicitudesMaterialesProducto)) {
            // set the owning side to null (unless already changed)
            if ($solicitudesMaterialesProducto->getSolicitudMateriales() === $this) {
                $solicitudesMaterialesProducto->setSolicitudMateriales(null);
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
            $fileDocumento->setSolicitudMateriales($this);
        }

        return $this;
    }

    public function removeFileDocumento(FileDocumento $fileDocumento): self
    {
        if ($this->fileDocumentos->removeElement($fileDocumento)) {
            // set the owning side to null (unless already changed)
            if ($fileDocumento->getSolicitudMateriales() === $this) {
                $fileDocumento->setSolicitudMateriales(null);
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
            $comentario->setSolicitudMateriales($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getSolicitudMateriales() === $this) {
                $comentario->setSolicitudMateriales(null);
            }
        }

        return $this;
    }



}
