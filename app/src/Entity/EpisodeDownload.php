<?php

namespace App\Entity;

use App\Entity\Traits\UuidTrait;
use App\Repository\EpisodeDownloadsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeDownloadsRepository::class)
 * @ORM\Table(name="episode_downloads")
 */
class EpisodeDownload
{
    use UuidTrait;

     /**
      * Each download can have one episode
     * @ORM\ManyToOne(targetEntity=Episode::class, inversedBy="episode_downloads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $episode;

    /**
     * Each download can have one podcast
     * @ORM\ManyToOne(targetEntity=Podcast::class, inversedBy="episode_downloads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $podcast;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $occured_at;

    /**
     * @return mixed
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * @param mixed $episode
     */
    public function setEpisode($episode): void
    {
        $this->episode = $episode;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPodcast()
    {
        return $this->podcast;
    }

    /**
     * @param mixed $podcast
     */
    public function setPodcast($podcast): void
    {
        $this->podcast = $podcast;
    }


    public function getOccuredAt(): ?\DateTimeImmutable
    {
        return $this->occured_at;
    }

    public function setOccuredAt(\DateTimeImmutable $occured_at): self
    {
        $this->occured_at = $occured_at;

        return $this;
    }
}
