<?php

namespace App\Entity;

use App\Entity\Traits\UuidTrait;
use App\Repository\EpisodesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=EpisodesRepository::class)
 * @ORM\Table(name="episodes")
 */
class Episode
{
    use UuidTrait;

    /**
     * Each episode can only have one podcast
     * @ORM\ManyToOne(targetEntity=Podcast::class, inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $podcast;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * Each episode can have many downloads
     * @ORM\OneToMany(targetEntity=EpisodeDownload::class, mappedBy="episode")
     * @ORM\JoinColumn(nullable=false)
     */
    private $episode_downloads;

    public function __construct()
    {
        $this->episode_downloads = new ArrayCollection();
    }

    public function getPodcast(): ?Podcast
    {
        return $this->podcast;
    }

    public function setPodcast(?Podcast $podcast): self
    {
        $this->podcast = $podcast;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|EpisodeDownload[]
     */
    public function getEpisodeDownloads(): Collection
    {
        return $this->episode_downloads;
    }

    public function addEpisodeDownload(EpisodeDownload $episode_download): self
    {
        if (!$this->episode_downloads->contains($episode_download)) {
            $this->episode_downloads[] = $episode_download;
            $episode_download->setEpisode($this);
        }

        return $this;
    }

    public function removeEpisodeDownload(EpisodeDownload $episode_download): self
    {
        if ($this->episode_downloads->removeElement($episode_download)) {
            // set the owning side to null (unless already changed)
            if ($episode_download->getEpisode() === $this) {
                $episode_download->setEpisode(null);
            }
        }

        return $this;
    }
}
