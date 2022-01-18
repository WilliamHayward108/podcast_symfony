<?php

namespace App\Event;

use App\Entity\Episode;
use App\Entity\Podcast;
use Symfony\Contracts\EventDispatcher\Event;

class EpisodeDownloadEvent extends Event
{
    public const NAME = 'episode_download.downloaded';

    protected $episode;

    protected $podcast;

    protected $occured_at;

    public function __construct(Episode $episode, Podcast $podcast, \DateTimeImmutable $occured_at)
    {
        $this->episode = $episode;
        $this->podcast = $podcast;
        $this->occured_at = $occured_at;
    }

    /**
     * @return Episode
     */
    public function getEpisode(): Episode
    {
        return $this->episode;
    }


    /**
     * @return \DateTimeImmutable
     */
    public function getOccuredAt(): \DateTimeImmutable
    {
        return $this->occured_at;
    }


}