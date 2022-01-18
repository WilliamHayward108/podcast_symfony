<?php

namespace App\Tests\Repository;

use App\Entity\EpisodeDownload;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EpisodeDownloadsRepositoryTest extends KernelTestCase
{
    private $episode_download_repository;
    private $manager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->manager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->episode_download_repository = $this->manager->getRepository(EpisodeDownload::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->manager->close();
        $this->manager = null;
    }

    //Test saving and persisting functionality of an episode download
    public function testSave(): void
    {
        $episode = $this->manager->getRepository(Episode::class)->findOneBy(['name' => 'episode 2']);
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2022-02-01 01:00:00');
        $episode_downloaded = new EpisodeDownload($episode, $episode->getPodcast(), $date);

        $this->episode_download_repository->save($episode_downloaded);

        $in_repo_download = $this->episode_download_repository->findOneBy(['episode' => $episode, 'occured_at' => $date]);

        $this->assertInstanceOf(EpisodeDownload::class, $in_repo_download);
        $this->assertEquals($episode, $in_repo_download->getEpisode());
        $this->assertEquals($date, $in_repo_download->getOccuredAt());
    }

    //Test repository function is called and returns an array that contains the created episode download and its value
    public function testDownloadsWithinPeriodForEpisode(): void
    {
        $episode = $this->manager->getRepository(Episode::class)->findOneBy(['name' => 'episode 3']);
        $episode_uuid = $episode->getUuidString();

        //Newdate has to be created as date will have to be within 7 days (by default) to be returned
        $date = new \DateTimeImmutable();
        $episode_downloaded = new EpisodeDownload($episode, $episode->getPodcast(), $date);
        $this->episode_download_repository->save($episode_downloaded);

        $result = $this->episode_download_repository->getDownloadsWithinPeriodForEpisode($episode_uuid);

        //Check result has the array keys of the dates we created in Y-m-d  format
        $this->assertArrayHasKey($date->format('Y-m-d'), $result);
        //Assert array values match expected download values
        $this->assertCount(1, $result);
        $this->assertEquals(1, $result[$date->format('Y-m-d')]);
    }

    //Test that statistics function returns an empty array when episode to be found is older than date period
    public function testStatisticsFunctionReturnsEmpty(): void
    {
        $episode = $this->manager->getRepository(Episode::class)->findOneBy(['name' => 'episode 4']);
        $episode_uuid = $episode->getUuidString();

        //Newdate with date before 7 days
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2022-01-01 01:00:00');
        $episode_downloaded = new EpisodeDownload($episode, $episode->getPodcast(), $date);
        $this->episode_download_repository->save($episode_downloaded);

        $result = $this->episode_download_repository->getDownloadsWithinPeriodForEpisode($episode_uuid);

        //Check result is empty array
        $this->assertEmpty($result);
    }
}