<?php

namespace App\Repository;

use App\Entity\EpisodeDownload;
use DateInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @method EpisodeDownload|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeDownload|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeDownload[]    findAll()
 * @method EpisodeDownload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeDownloadsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeDownload::class);
    }

    public function getDownloadsWithinPeriodForEpisode(string $episode_id, int $day_period = 7): array
    {
        $uuid = Uuid::fromString($episode_id);

        $now = new \DateTime();
        $days_ago_period = new \DateTime($day_period.' days ago');

        $qb = $this->createQueryBuilder('ed', 'ed.occured_at');
        $qb->select('count(ed) as episode_count, ed.occured_at')
            ->add('where', $qb->expr()->between(
                    'ed.occured_at',
                    ':days_ago',
                    ':now'
                ))
            ->andWhere('ed.episode = :episode_id')
            ->setParameter('episode_id', $uuid, 'uuid')
            ->setParameter('days_ago', $days_ago_period)
            ->setParameter('now', $now)
            ->groupBy('ed.occured_at');

        $query_result = $qb->getQuery()->getArrayResult();

        $formatted_result = [];

        //Format out put so [downloaded_date] => times downloaded
        foreach ($query_result as $key => $value){
            $formatted_date = \DateTime::createFromFormat('Y-m-d H:i:s', $key)->format('Y-m-d');
            $formatted_result[$formatted_date] = $value['episode_count'];
        }

        return $formatted_result;
    }
}
