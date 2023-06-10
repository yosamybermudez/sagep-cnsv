<?php

namespace App\Entity;

use App\Controller\BaseController;
use App\Repository\FileDocumentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @ORM\Entity(repositoryClass=FileDocumentoRepository::class)
 */
class FileDocumento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $nombreReal;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $tipo;


    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="fileDocumentos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Encuentro::class, inversedBy="fileDocumentos")
     */
    private $encuentro;

    /**
     * @ORM\ManyToOne(targetEntity=SolicitudMateriales::class, inversedBy="fileDocumentos")
     */
    private $solicitudMateriales;

    /**
     * @ORM\ManyToOne(targetEntity=EncuentroAcuerdo::class, inversedBy="fileDocumentos")
     */
    private $encuentroAcuerdo;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_subido", type="datetime", nullable=true)
     */
    private $fechaSubido;

    public function __construct(BaseController $controller)
    {
        $this->encuentro = new ArrayCollection();
        $this->user=$controller->getDatabaseUser();
        $this->fechaSubido= new \DateTime();
//        $this->id = intval(substr(md5('converge'),0,8),16)>>1;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreReal(): ?string
    {
        return $this->nombreReal;
    }

    public function setNombreReal(string $nombreReal): self
    {
        $this->nombreReal = $nombreReal;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
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

    public function getFechaSubido(): ?\DateTimeInterface
    {
        return $this->fechaSubido;
    }

    public function setFechaSubido(?\DateTimeInterface $fechaSubido): self
    {
        $this->fechaSubido = $fechaSubido;

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

    public function getEncuentroAcuerdo(): ?EncuentroAcuerdo
    {
        return $this->encuentroAcuerdo;
    }

    public function setEncuentroAcuerdo(?EncuentroAcuerdo $encuentroAcuerdo): self
    {
        $this->encuentroAcuerdo = $encuentroAcuerdo;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getSolicitudMateriales(): ?SolicitudMateriales
    {
        return $this->solicitudMateriales;
    }

    public function setSolicitudMateriales(?SolicitudMateriales $solicitudMateriales): self
    {
        $this->solicitudMateriales = $solicitudMateriales;

        return $this;
    }


}
