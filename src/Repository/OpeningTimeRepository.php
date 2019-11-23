<?php

namespace App\Repository;

use App\Entity\OpeningTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OpeningTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method OpeningTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method OpeningTime[]    findAll()
 * @method OpeningTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpeningTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpeningTime::class);
    }


    public function getOrdered()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.DayOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return OpeningTime[] Returns an array of OpeningTime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OpeningTime
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
