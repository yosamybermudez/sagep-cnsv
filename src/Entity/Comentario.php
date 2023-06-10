<?php

namespace App\Entity;


use App\Controller\BaseController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ComentarioRepository;

/**
 * @ORM\Entity(repositoryClass=ComentarioRepository::class)
 */
class Comentario
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
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=true)
     */
    private $fechaCreado;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_modificado", type="datetime", nullable=true)
     */
    private $fechaModificado;
    /**
     * @var string|null
     *
     * @ORM\Column(name="comentario", type="string", nullable=false)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuarioUltimaModificacion;

    /**
     * @ORM\ManyToOne(targetEntity=Encuentro::class, inversedBy="comentarios")
     */
    private $encuentro;

    /**
     * @ORM\ManyToOne(targetEntity=Comentario::class, inversedBy="comentarios")
     */
    private $comentarioPadre;

    /**
     * @ORM\OneToMany(targetEntity=Comentario::class, mappedBy="comentarioPadre")
     */
    private $respuestas;

    /**
     * @ORM\ManyToOne(targetEntity=EncuentroAcuerdo::class, inversedBy="fileDocumentos")
     */
    private $encuentroAcuerdo;


    /**
     * @ORM\ManyToOne(targetEntity=SolicitudMateriales::class, inversedBy="comentario")
     */
    private $solicitudMateriales;

    public function __construct(BaseController $controller)
    {
//        $this->id = intval(substr(md5('converge'),0,8),16)>>1;
        $this->usuario = $controller->getDatabaseUser();
        $this->usuarioUltimaModificacion = $controller->getDatabaseUser();
        $this->fechaCreado = new \DateTime();
        $this->fechaModificado = new \DateTime();
        $this->respuestas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaCreado(): ?\DateTimeInterface
    {
        return $this->fechaCreado;
    }

    public function setFechaCreado(?\DateTimeInterface $fechaCreado): self
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    public function getFechaModificado(): ?\DateTimeInterface
    {
        return $this->fechaModificado;
    }

    public function setFechaModificado(?\DateTimeInterface $fechaModificado): self
    {
        $this->fechaModificado = $fechaModificado;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): self
    {
        $this->comentario = $comentario;

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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getUsuarioUltimaModificacion(): ?Usuario
    {
        return $this->usuarioUltimaModificacion;
    }

    public function setUsuarioUltimaModificacion(?Usuario $usuarioUltimaModificacion): self
    {
        $this->usuarioUltimaModificacion = $usuarioUltimaModificacion;

        return $this;
    }

    public function getComentarioPadre(): ?self
    {
        return $this->comentarioPadre;
    }

    public function setComentarioPadre(?self $comentarioPadre): self
    {
        $this->comentarioPadre = $comentarioPadre;

        return $this;
    }

    /**
     * @return Collection|Comentario[]
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(Comentario $respuesta): self
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas[] = $respuesta;
            $respuesta->setComentarioPadre($this);
        }

        return $this;
    }

    public function removeRespuesta(Comentario $respuesta): self
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getComentarioPadre() === $this) {
                $respuesta->setComentarioPadre(null);
            }
        }

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
