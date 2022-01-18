<?php

namespace App\EventListener;

use App\Entity\EpisodeDownload;
use App\Event\EpisodeDownloadEvent;
use App\Repository\EpisodeDownloadsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EpisodeDownloadedSubscriber implements EventSubscriberInterface
{
    protected $manager;
    protected $episode_downloads_repository;

    public function __construct(EntityManagerInterface $manager, EpisodeDownloadsRepository $episode_downloads_repository)
    {
        $this->manager = $manager;
        $this->episode_downloads_repository = $episode_downloads_repository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EpisodeDownloadEvent::NAME => [
                'onEpisodeDownload'
            ]
        ];
    }

    public function onEpisodeDownload(EpisodeDownloadEvent $event)
    {
        $episode_download = new EpisodeDownload($event->getEpisode(), $event->getOccuredAt());

        $this->episode_downloads_repository->save($episode_download);
    }
}