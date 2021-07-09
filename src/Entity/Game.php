<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $launchAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $noteGlobal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pathImg;

    /**
     * @ORM\ManyToMany(targetEntity=GameCategory::class, inversedBy="games")
     */
    private $gameCategories;

    /**
     * @ORM\ManyToMany(targetEntity=Device::class, inversedBy="games")
     */
    private $devices;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, inversedBy="game", cascade={"persist", "remove"})
     */
    private $Forum;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, mappedBy="Forum", cascade={"persist", "remove"})
     */
    private $game;

    /**
     * @ORM\OneToMany(targetEntity=Forum::class, mappedBy="game")
     */
    private $Topic;

    /**
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="game")
     */
    private $Message;

    /**
     * @ORM\OneToMany(targetEntity=PostCategory::class, mappedBy="postCategory")
     */
    private $Post;

    public function __construct()
    {
        $this->gameCategories = new ArrayCollection();
        $this->devices = new ArrayCollection();
        $this->Topic = new ArrayCollection();
        $this->Message = new ArrayCollection();
        $this->Post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLaunchAt(): ?\DateTimeInterface
    {
        return $this->launchAt;
    }

    public function setLaunchAt(\DateTimeInterface $launchAt): self
    {
        $this->launchAt = $launchAt;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNoteGlobal(): ?int
    {
        return $this->noteGlobal;
    }

    public function setNoteGlobal(int $noteGlobal): self
    {
        $this->noteGlobal = $noteGlobal;

        return $this;
    }

    public function getPathImg(): ?string
    {
        return $this->pathImg;
    }

    public function setPathImg(string $pathImg): self
    {
        $this->pathImg = $pathImg;

        return $this;
    }

    /**
     * @return Collection|GameCategory[]
     */
    public function getGameCategories(): Collection
    {
        return $this->gameCategories;
    }

    public function addGameCategory(GameCategory $gameCategory): self
    {
        if (!$this->gameCategories->contains($gameCategory)) {
            $this->gameCategories[] = $gameCategory;
        }

        return $this;
    }

    public function removeGameCategory(GameCategory $gameCategory): self
    {
        $this->gameCategories->removeElement($gameCategory);

        return $this;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        $this->devices->removeElement($device);

        return $this;
    }

    public function getForum(): ?self
    {
        return $this->Forum;
    }

    public function setForum(?self $Forum): self
    {
        $this->Forum = $Forum;

        return $this;
    }

    public function getGame(): ?self
    {
        return $this->game;
    }

    public function setGame(?self $game): self
    {
        // unset the owning side of the relation if necessary
        if ($game === null && $this->game !== null) {
            $this->game->setForum(null);
        }

        // set the owning side of the relation if necessary
        if ($game !== null && $game->getForum() !== $this) {
            $game->setForum($this);
        }

        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection|Forum[]
     */
    public function getTopic(): Collection
    {
        return $this->Topic;
    }

    public function addTopic(Forum $topic): self
    {
        if (!$this->Topic->contains($topic)) {
            $this->Topic[] = $topic;
            $topic->setGame($this);
        }

        return $this;
    }

    public function removeTopic(Forum $topic): self
    {
        if ($this->Topic->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getGame() === $this) {
                $topic->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getMessage(): Collection
    {
        return $this->Message;
    }

    public function addMessage(Topic $message): self
    {
        if (!$this->Message->contains($message)) {
            $this->Message[] = $message;
            $message->setGame($this);
        }

        return $this;
    }

    public function removeMessage(Topic $message): self
    {
        if ($this->Message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getGame() === $this) {
                $message->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostCategory[]
     */
    public function getPost(): Collection
    {
        return $this->Post;
    }

    public function addPost(PostCategory $post): self
    {
        if (!$this->Post->contains($post)) {
            $this->Post[] = $post;
            $post->setPostCategory($this);
        }

        return $this;
    }

    public function removePost(PostCategory $post): self
    {
        if ($this->Post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getPostCategory() === $this) {
                $post->setPostCategory(null);
            }
        }

        return $this;
    }
}
