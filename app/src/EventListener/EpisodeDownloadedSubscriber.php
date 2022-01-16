<?php

namespace App\EventListener;

use App\Entity\EpisodeDownload;
use App\Event\EpisodeDownloadEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EpisodeDownloadedSubscriber implements EventSubscriberInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
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
        $episode_download = new EpisodeDownload();
        $episode_download->setEpisode($event->getEpisode());
        $episode_download->setPodcast($event->getPodcast());
        $episode_download->setOccuredAt($event->getOccuredAt());

        $this->manager->persist($episode_download);
        $this->manager->flush();
    }
}