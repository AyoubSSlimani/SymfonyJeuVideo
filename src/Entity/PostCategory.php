<?php

namespace App\Entity;

use App\Repository\PostCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostCategoryRepository::class)
 */
class PostCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="Post")
     */
    private $postCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPostCategory(): ?Game
    {
        return $this->postCategory;
    }

    public function setPostCategory(?Game $postCategory): self
    {
        $this->postCategory = $postCategory;

        return $this;
    }
}
