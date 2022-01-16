<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Event\EpisodeDownloadEvent;
use App\Repository\EpisodeDownloadsRepository;
use Cassandra\UuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class EpisodeDownloadController extends AbstractController
{
    /**
     * @Route("/getEpisodeStatistics/{uuid}/{period}", defaults={"period"=7}, name="episode_statistics")
     */
    public function getEpisodesStatistics(string $uuid, int $period, Request $request, EpisodeDownloadsRepository $episode_downloads_repository): JsonResponse
    {
        $downloads_data = $episode_downloads_repository->getDownloadsWithinPeriodForEpisode($uuid, $period);

        return new JsonResponse([
            'success' => true,
            'data' => $downloads_data
        ]);
    }

    /**
     * @Route("/episodeDownloaded/{uuid}", name="episode_download")
     */
    public function episodeDownloaded(string $uuid, EventDispatcherInterface $event_dispatcher, EntityManagerInterface $manager): JsonResponse
    {
        $episode_uuid = Uuid::fromString($uuid);
        $episode = $manager->getRepository(Episode::class)->findOneBy(['id' => $episode_uuid]);

        if($episode){
            $downloaded_event = new EpisodeDownloadEvent($episode, $episode->getPodcast(), new \DateTimeImmutable());
            $event_dispatcher->dispatch($downloaded_event, EpisodeDownloadEvent::NAME);

            return new JsonResponse([
                'success' => true
            ], 200);
        }else{
            return new JsonResponse([
                'success' => false,
                'error' => 'Provided uuid is not an episode'
            ], 404);
        }

    }
}
