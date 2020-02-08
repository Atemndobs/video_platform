<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 * @ORM\Table(name="videos", indexes={@Index(name="title_idx", columns={"title"})})
 */
class Video
{
  //  public const videoForNotLoggedIn = 289729765; // vimeo video id
    public const videoForNotLoggedInOrNoMembers = 289729765; // vimeo video id
    public const VimeoPath = 'https://player.vimeo.com/video/';
    public const perPage = 5; // for pagination


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="videos")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="video")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="likedVideos")
     * @ORM\JoinTable(name="likes")
     */
    private $usersThatlike;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="dislikedVideos")
     * @ORM\JoinTable(name="dislikes")
     */
    private $usersThatDontLike;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersThatlike = new ArrayCollection();
        $this->usersThatDontLike = new ArrayCollection();
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getVimeoId(): ?string
    {
/*        if ($user) {
            return $this->path;
        }
        else return self::VimeoPath.self::videoForNotLoggedInOrNoMembers;
*/
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     * @return Video
     */
    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Video
     */
    public function setCategory($category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVideo($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getVideo() === $this) {
                $comment->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersThatlike(): Collection
    {
        return $this->usersThatlike;
    }

    public function addUsersThatlike(User $usersThatlike): self
    {
        if (!$this->usersThatlike->contains($usersThatlike)) {
            $this->usersThatlike[] = $usersThatlike;
        }

        return $this;
    }

    public function removeUsersThatlike(User $usersThatlike): self
    {
        if ($this->usersThatlike->contains($usersThatlike)) {
            $this->usersThatlike->removeElement($usersThatlike);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersThatDontLike(): Collection
    {
        return $this->usersThatDontLike;
    }

    public function addUsersThatDontLike(User $usersThatDontLike): self
    {
        if (!$this->usersThatDontLike->contains($usersThatDontLike)) {
            $this->usersThatDontLike[] = $usersThatDontLike;
        }

        return $this;
    }

    public function removeUsersThatDontLike(User $usersThatDontLike): self
    {
        if ($this->usersThatDontLike->contains($usersThatDontLike)) {
            $this->usersThatDontLike->removeElement($usersThatDontLike);
        }

        return $this;
    }

}
