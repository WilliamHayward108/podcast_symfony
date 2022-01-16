<?php

namespace App\Fixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 21; $i++)
        {
            $episode = New Episode();
            $episode->setName('episode '.$i);
            $episode->setCreatedAt(new \DateTimeImmutable());
            $podcast_ref = $this->getReference('podcast ref-'.rand(1, 5));
            $episode->setPodcast($podcast_ref);
            $this->addReference('episode-'.$i, $episode);

            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PodcastFixtures::class
        ];
    }
}