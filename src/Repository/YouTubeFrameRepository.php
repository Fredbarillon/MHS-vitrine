<?php

namespace App\Repository;

use App\Entity\YouTubeFrame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<YouTubeFrame>
 *
 * @method YouTubeFrame|null find($id, $lockMode = null, $lockVersion = null)
 * @method YouTubeFrame|null findOneBy(array $criteria, array $orderBy = null)
 * @method YouTubeFrame[]    findAll()
 * @method YouTubeFrame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YouTubeFrameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YouTubeFrame::class);
    }

//    /**
//     * @return YouTubeFrame[] Returns an array of YouTubeFrame objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('y')
//            ->andWhere('y.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('y.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?YouTubeFrame
//    {
//        return $this->createQueryBuilder('y')
//            ->andWhere('y.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
