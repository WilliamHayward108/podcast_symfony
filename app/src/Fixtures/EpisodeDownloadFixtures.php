<?php

namespace App\Fixtures;

use App\Entity\EpisodeDownload;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeDownloadFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $download = new EpisodeDownload();
            $download->setOccuredAt(new \DateTimeImmutable());
            $episode_ref = $this->getReference('episode-'.rand(1, 20));
            $download->setEpisode($episode_ref);
            $download->setPodcast($episode_ref->getPodcast());

            $manager->persist($download);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PodcastFixtures::class,
            EpisodeFixtures::class
        ];
    }
}