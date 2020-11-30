<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @UniqueEntity(
 *     fields={"mail"},
 *     groups={"registration"},
 *     message="Veuillez choisir une autre adresse mail, celui ci est deja pris"
 * )
 */
class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255, nullable=false)
     * @Assert\NotNull(groups={"registration"})
     * @Assert\NotBlank(groups={"registration"})
     */
    public $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=255, unique=true, nullable=false)
     * @Assert\Email(message="Veuillez entrer une adresse mail valide" ,groups={"registration"})
     * @Assert\NotNull(groups={"registration"})
     */
    public $mail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Assert\Length(
     *     min="8",
     *     minMessage="Le mot de passe doit contenir au minimum 8 caracteres",
     *     groups={"registration"}
     * )
     * @Assert\NotNull(groups={"registration"})
     * @Assert\NotBlank(groups={"registration"})
     */
    public $password;

    /**
     * @var string
     * @Assert\EqualTo(
     *     propertyPath = "password",
     *     message= "Le mot de passe doit etre identique",
     *     groups={"registration"}
     * )
     * @Assert\NotBlank(groups={"registration"})
     */
    private $confirmPassword;

    /**
     * @var string null|string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $img;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $modifiedAt;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    public $active = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="user")
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return User
     */
    public function setMail(string $mail): User
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    /**
     * @param string $confirmPassword
     * @return User
     */
    public function setConfirmPassword(string $confirmPassword): User
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getImg(): ?string
    {
        return $this->img;
    }

    /**
     * @param string $img
     * @return User
     */
    public function setImg(string $img): User
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedAt(): \DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @param \DateTime $modifiedAt
     * @return User
     */
    public function setModifiedAt(\DateTime $modifiedAt): User
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return User
     */
    public function setActive(bool $active): User
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(){}

    /**
     * @see UserInterface
     */
    public function eraseCredentials(){}

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return strtolower(str_replace(" ","_", $this->fullName));
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    /**
     * @param Collection $tricks
     * @return $this
     */
    public function setTricks(Collection $tricks)
    {
        $this->tricks = $tricks;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Collection $comments
     * @return $this
     */
    public function setComments(Collection $comments)
    {
        $this->comments = $comments;

        return $this;
    }
}

