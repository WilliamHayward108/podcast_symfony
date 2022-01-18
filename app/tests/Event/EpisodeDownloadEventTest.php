<?php

namespace App\Tests\Event;

use App\Entity\Episode;
use App\Entity\EpisodeDownload;
use App\Event\EpisodeDownloadEvent;
use App\EventListener\EpisodeDownloadedSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EpisodeDownloadEventTest extends KernelTestCase
{
    private $manager;
    private $dispatcher;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->manager = $kernel->getContainer()->get('doctrine')->getManager();
        $episode_download_repository = $this->manager->getRepository(EpisodeDownload::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new EpisodeDownloadedSubscriber($this->manager, $episode_download_repository));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->manager->close();
        $this->manager = null;
    }

    public function testDownloadEventSubscriberDownloadsEpisode(): void
    {
        $episode = $this->manager->getRepository(Episode::class)->findOneBy(['name' => 'episode 2']);
        $date_time = new \DateTimeImmutable();


        $event = new EpisodeDownloadEvent($episode, $episode->getPodcast(), $date_time);
        $this->dispatcher->dispatch($event, EpisodeDownloadEvent::NAME);

        $episode_downloaded = $this->manager->getRepository(EpisodeDownload::class)->findBy([
            'episode' => $episode,
            'occured_at' => $date_time
        ]);


        $this->assertEquals($episode_downloaded[0]->getEpisode(), $episode);
        $this->assertEquals($episode_downloaded[0]->getOccuredAt(), $date_time);
    }
}
