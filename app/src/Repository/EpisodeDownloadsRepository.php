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

    public function save(EpisodeDownload $episode_download){
        $this->getEntityManager()->persist($episode_download);
        $this->getEntityManager()->flush();
    }

    public function getDownloadsWithinPeriodForEpisode(string $episode_id, int $day_period = 7): array
    {
        $uuid = Uuid::fromString($episode_id);

        $now = new \DateTime();
        $days_ago_period = new \DateTime($day_period.' days ago');

        $qb = $this->createQueryBuilder('ed');
        $qb->select('count(ed) as episode_count, DATE(ed.occured_at) as date')
            ->where('(DATE(ed.occured_at) between :days_ago and :now)')
            ->andWhere('ed.episode = :episode_id')
            ->setParameter('episode_id', $uuid, 'uuid')
            ->setParameter('days_ago', $days_ago_period->format('Y-m-d'))
            ->setParameter('now', $now->format('Y-m-d'))
            ->groupBy('date');

        $data = $qb->getQuery()->getArrayResult();

        //No downloads found for episode/range so return
        if(empty($data)){
            return [];
        }

        $output = [];
        foreach ($data as $datum)
        {
            //Check correct keys exist
            if(isset($datum['episode_count']) && isset($datum['date'])){
                $output[$datum['date']] = $datum['episode_count'];
            }
        }

        return $output;
    }
}
