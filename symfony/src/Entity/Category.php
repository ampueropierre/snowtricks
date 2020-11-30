<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *@ORM\Entity()
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var Trick
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="category")
     */
    private $tricks;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
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
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this;
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Trick
     */
    public function getTricks(): Trick
    {
        return $this->tricks;
    }

    /**
     * @param Trick $trick
     *
     * @return $this
     */
    public function setTricks(Trick $trick)
    {
        $this->tricks = $trick;

        return $this;
    }

    /**
     * @param Trick $trick
     * @return $this
     */
    public function addTrick(Trick $trick)
    {
        $this->tricks[] = $trick;
        $trick->setCategory($this);

        return $this;
    }

    /**
     * @param Trick $trick
     * @return $this
     */
    public function removeTrick(Trick $trick)
    {
        $this->tricks->removeElement($trick);

        if ($trick->getCategory() === $this) {
            $trick->setCategory(null);
        }

        return $this;
    }
}

