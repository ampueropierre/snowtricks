<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *@ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 *@ORM\Table(name="comment")
 */
class Comment
{
    /**
     *@ORM\Id()
     *@ORM\GeneratedValue()
     *@ORM\Column(type="integer")
     */
    private $id;

    /**
     *@ORM\Column(type="text", nullable=false, length=255)
     *@Assert\NotNull(groups={"new_comment"})
     *@Assert\NotBlank(groups={"new_comment"})
     *@Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le message doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le message ne doit pas contenir plus de {{ limit }} caractères",
     *      groups={"new_comment"}
     *)
     */
    private $message;

    /**
     *@ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     *@ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     *@ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *@ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="comments")
     *@ORM\JoinColumn(name="trick_id", referencedColumnName="id")
     */
    private $trick;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return Comment
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Comment
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Comment
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Trick
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    /**
     * @param Trick $trick
     * @return Comment
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }
}

