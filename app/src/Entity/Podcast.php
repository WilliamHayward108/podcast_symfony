<?php

namespace App\Entity;

use App\Entity\Traits\UuidTrait;
use App\Repository\PodcastsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PodcastsRepository::class)
 * @ORM\Table(name="podcasts")
 */
class Podcast
{
    use UuidTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * One podcast can have many episodes
     * @ORM\OneToMany(targetEntity=Episode::class, mappedBy="podcast")
     */
    private $episodes;

    public function __construct(string $name, \DateTimeImmutable $created_at)
    {
        $this->episodes = new ArrayCollection();
        $this->name = $name;
        $this->created_at = $created_at;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setPodcast($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getPodcast() === $this) {
                $episode->setPodcast(null);
            }
        }

        return $this;
    }
}
