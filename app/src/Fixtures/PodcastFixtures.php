<?php

namespace App\Fixtures;

use App\Entity\Podcast;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PodcastFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 6; $i++)
        {
            $podcast = new Podcast();
            $podcast->setName("podcast ". $i);
            $podcast->setCreatedAt(new \DateTimeImmutable());
            $this->addReference("podcast ref-".$i, $podcast);
            $manager->persist($podcast);
        }

        $manager->flush();
    }
}