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
     * @ORM\Column(type="datetime_immutable")
     */
    private $occured_at;

    public function __construct(Episode $episode, \DateTimeImmutable $occured_at)
    {
        $this->episode = $episode;
        $this->occured_at = $occured_at;
    }

    /**
     * @param mixed $episode
     */
    public function setEpisode(Episode $episode): void
    {
        $this->episode = $episode;
    }

    /**
     * @return mixed
     */
    public function getEpisode(): Episode
    {
        return $this->episode;
    }


    public function getOccuredAt(): \DateTimeImmutable
    {
        return $this->occured_at;
    }

}
