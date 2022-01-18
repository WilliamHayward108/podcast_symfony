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
        //Change to use static dates for easier testing
        for($i = 0; $i < 10; $i++){
            //Create data starting with ref id of 7, 1-6 are reserved for specific tests
            $episode_ref = $this->getReference('episode-'.rand(7, 20));
            $download = new EpisodeDownload($episode_ref, \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2022-01-01 12:00:00'));
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