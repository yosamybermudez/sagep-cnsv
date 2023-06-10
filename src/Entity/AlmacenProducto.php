<?php

namespace App\Entity;

use App\Controller\BaseController;
use App\Repository\AlmacenProductoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass=AlmacenProductoRepository::class)
 */
class AlmacenProducto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="unidad_medida", type="string", nullable=true)
     */
    private $unidadMedida;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codigo", type="string", nullable=true)
     */
    private $codigo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="string", nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="f_create", type="datetime", nullable=true)
     */
    private $fCreate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="f_update", type="datetime", nullable=true)
     */
    private $fUpdate;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="u_create", referencedColumnName="id")
     * })
     */
    private $uCreate;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="u_update", referencedColumnName="id")
     * })
     */
    private $uUpdate;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\SolicitudMaterialesProductos", mappedBy="solicitudMateriales")
     */
    private $solicitudesMaterialesProductos;


    public function __construct(BaseController $controller)
    {
        $this->uCreate = $controller->getDatabaseUser();
        $this->fCreate = new \DateTime();
        $this->solicitudesMaterialesProductos = new ArrayCollection();
    }

    public function getCodigoDescripcion(){
        return $this->getCodigo() . " - " . $this->descripcion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnidadMedida(): ?string
    {
        return $this->unidadMedida;
    }

    public function setUnidadMedida(?string $unidadMedida): self
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo != '' ? $this->codigo : 'S/C';
    }

    public function setCodigo(?string $codigo): self
    {
        $this->codigo = $codigo;

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

    public function getFCreate(): ?\DateTimeInterface
    {
        return $this->fCreate;
    }

    public function setFCreate(?\DateTimeInterface $fCreate): self
    {
        $this->fCreate = $fCreate;

        return $this;
    }

    public function getFUpdate(): ?\DateTimeInterface
    {
        return $this->fUpdate;
    }

    public function setFUpdate(?\DateTimeInterface $fUpdate): self
    {
        $this->fUpdate = $fUpdate;

        return $this;
    }

    public function getUCreate(): ?Usuario
    {
        return $this->uCreate;
    }

    public function setUCreate(?Usuario $uCreate): self
    {
        $this->uCreate = $uCreate;

        return $this;
    }

    public function getUUpdate(): ?Usuario
    {
        return $this->uUpdate;
    }

    public function setUUpdate(?Usuario $uUpdate): self
    {
        $this->uUpdate = $uUpdate;

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
}
