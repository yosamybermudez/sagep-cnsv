<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class Usuario implements UserInterface, EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank( message="Ne doit pas être vide")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ne doit pas être vide")
     */
    private $nombreCompleto;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Ne doit pas être vide")
     * @Assert\Email(message="Email invalide")
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255))
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=EventoSistema::class, mappedBy="user")
     */
    private $eventosSistema;

    /**
     * @ORM\OneToMany(targetEntity=FileDocumento::class, mappedBy="user")
     */
    private $fileDocumentos;

    /**
     * @ORM\OneToOne(targetEntity=Trabajador::class, inversedBy="usuario", cascade={"persist", "remove"})
     */
    private $trabajadorAsociado;


    public function __construct()
    {
        $this->eventosSistema = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombreCompleto(): ?string
    {
        return $this->nombreCompleto;
    }

    public function setNombreCompleto($nombreCompleto): self
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail( $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatarUrl($size){
        return "https://api.adorable.io/avatars/$size/".$this->username;
    }


    function getColorCode() {
        $code = dechex(crc32($this->getUsername()));
        $code = substr($code, 0, 6);
        return "#".$code;
    }

    /**
     * @Assert\Callback
     */

    public function validate(ExecutionContextInterface $context, $payload)
    {
        /*if (strlen($this->password)< 3){
            $context->buildViolation('Mot de passe trop court')
                ->atPath('justpassword')
                ->addViolation();
        }*/
    }

    public function __toString()
    {
        return "$this->nombreCompleto ($this->id)";
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof Usuario)
        return $this->isValid() && !$this->isDeleted() && $this->getPassword() == $user->getPassword() && $this->getUsername() == $user->getUsername()
            && $this->getEmail() == $user->getEmail() ;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    /**
     * @return Collection|EventoSistema[]
     */
    public function getEventosSistema(): Collection
    {
        return $this->eventosSistema;
    }

    public function addEventosSistema(EventoSistema $eventosSistema): self
    {
        if (!$this->eventosSistema->contains($eventosSistema)) {
            $this->eventosSistema[] = $eventosSistema;
            $eventosSistema->setUser($this);
        }

        return $this;
    }

    public function removeEventosSistema(EventoSistema $eventosSistema): self
    {
        if ($this->eventosSistema->removeElement($eventosSistema)) {
            // set the owning side to null (unless already changed)
            if ($eventosSistema->getUser() === $this) {
                $eventosSistema->setUser(null);
            }
        }

        return $this;
    }

    public function getTrabajadorAsociado(): ?Trabajador
    {
        return $this->trabajadorAsociado;
    }

    public function setTrabajadorAsociado(?Trabajador $trabajadorAsociado): self
    {
        $this->trabajadorAsociado = $trabajadorAsociado;

        return $this;
    }
}
