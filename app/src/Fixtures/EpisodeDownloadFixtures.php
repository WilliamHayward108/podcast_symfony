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
            $episode_ref = $this->getReference('episode-'.rand(1, 20));
            $download = new EpisodeDownload($episode_ref, $episode_ref->getPodcast(), new \DateTimeImmutable());
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