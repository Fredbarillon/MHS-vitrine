<?php

namespace App\Repository;

use App\Entity\Mobilhome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mobilhome>
 *
 * @method Mobilhome|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mobilhome|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mobilhome[]    findAll()
 * @method Mobilhome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobilhomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mobilhome::class);
    }

    public function findRecent($limit): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.created_at', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }




//    /**
//     * @return Mobilhome[] Returns an array of Mobilhome objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mobilhome
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
