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
            $name = "podcast ". $i;
            $podcast = new Podcast($name, new \DateTimeImmutable());
            $this->addReference("podcast ref-".$i, $podcast);
            $manager->persist($podcast);
        }

        $manager->flush();
    }
}