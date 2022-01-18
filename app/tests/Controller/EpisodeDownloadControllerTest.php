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
    private $episode_download_repository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = $this->client ->getContainer()->get('doctrine')->getManager();

        $this->episode = $this->manager->getRepository(Episode::class)->findOneBy(['name' => 'episode 1']);
        $podcast = $this->manager->getRepository(Podcast::class)->findOneBy(['name' => 'podcast 1']);
        $this->episode_download_repository = $this->manager->getRepository(EpisodeDownload::class);

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
        $date = new \DateTimeImmutable();
        $date_plus_day = new \DateTimeImmutable('-1 day');

        //Create two downloads on the same day, and one a day before
        $episode_downloaded = new EpisodeDownload($this->episode, $this->episode->getPodcast(), $date);
        $second_episode_downloaded = new EpisodeDownload($this->episode, $this->episode->getPodcast(), $date);
        $third_episode_downloaded = new EpisodeDownload($this->episode, $this->episode->getPodcast(), $date_plus_day);

        $this->episode_download_repository->save($episode_downloaded);
        $this->episode_download_repository->save($second_episode_downloaded);
        $this->episode_download_repository->save($third_episode_downloaded);

        //Call controller and decode json response
        $crawler = $this->client->request('GET', '/getEpisodeStatistics/'.$this->episode_uuid);
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();

        //Decode response data
        $response_data = json_decode($response->getContent(), true);

        //Check response has the array keys of the dates we created in Y-m-d  format
        $this->assertArrayHasKey($date->format('Y-m-d'), $response_data['data']);
        $this->assertArrayHasKey($date_plus_day->format('Y-m-d'), $response_data['data']);
        //Assert array values match expected download values
        $this->assertCount(2, $response_data['data']);
        $this->assertEquals(2, $response_data['data'][$date->format('Y-m-d')]);
        $this->assertEquals(1, $response_data['data'][$date_plus_day->format('Y-m-d')]);
    }
}
