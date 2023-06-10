<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=SolicitudMaterialesProductosRepository::class)
 */
class SolicitudMaterialesProductos
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="cantidad", type="string", nullable=true)
     */
    private $cantidad;

    /**
     * @var \SolicitudMateriales
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SolicitudMateriales")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="solicitud_materiales", referencedColumnName="id")
     * })
     */
    private $solicitudMateriales;

    /**
     * @var \AlmacenProducto
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AlmacenProducto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto", referencedColumnName="id")
     * })
     */
    private $producto;

    public function getCantidad(): ?string
    {
        return $this->cantidad;
    }

    public function setCantidad(?string $cantidad): self
    {
        $this->cantidad = $cantidad;

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

    public function getProducto(): ?AlmacenProducto
    {
        return $this->producto;
    }

    public function setProducto(?AlmacenProducto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }


}
