<?php

namespace App\Tests\Controller;

use App\Entity\Episode;
use App\Entity\EpisodeDownload;
use App\Entity\Podcast;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EpisodeDownloadControllerTest extends WebTestCase
{
    private $episode;
    private $episode_uuid;
    private $client;
    private $podcast;
    private $manager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = $this->client ->getContainer()->get('doctrine')->getManager();

        $this->episode = $this->manager->getRepository(Episode::class)->findOneBy(['name' => 'episode 1']);
        $podcast = $this->manager->getRepository(Podcast::class)->findOneBy(['name' => 'podcast 1']);

        $this->episode_uuid = $this->episode->getUuidString();
        $this->podcast = $podcast->getUuidString();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->manager->close();
        $this->manager = null;
    }

    public function testEpisodesStatisticsRoute(): void
    {
        $crawler = $this->client->request('GET', '/getEpisodeStatistics/'.$this->episode_uuid);

        $this->assertResponseIsSuccessful();
    }

    public function testEpisodesDownloadRoute(): void
    {
        $crawler = $this->client->request('GET', '/episodeDownloaded/'.$this->episode_uuid);

        $this->assertResponseIsSuccessful();
    }

    public function testEpisodesDownloadResponse(): void
    {
        $crawler = $this->client->request('GET', '/episodeDownloaded/'.$this->episode_uuid);
        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(200);
    }

    public function testEpisodesDownloadRouteReturnsError(): void
    {
        $crawler = $this->client->request('GET', '/episodeDownloaded/'.$this->podcast);
        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEpisodeStatisticsResponse(): void
    {
        //This test requires a new entity to be set up due to the day period on the repository function...
        //...without ensuring the entity was created when the test was ran we may get unintended results
        $date = new \DateTimeImmutable();
        $episode_downloaded = new EpisodeDownload();
        $episode_downloaded->setOccuredAt($date);
        $episode_downloaded->setEpisode($this->episode);
        $episode_downloaded->setPodcast($this->episode->getPodcast());
        $this->manager->persist($episode_downloaded);
        $this->manager->flush();

        $crawler = $this->client->request('GET', '/getEpisodeStatistics/'.$this->episode_uuid);
        $response = $this->client->getResponse();

        $response_data = json_decode($response->getContent(), true);
        dump($response_data['data']);
        $this->assertArrayHasKey($date->format('Y-m-d'), $response_data['data']);
    }
}
