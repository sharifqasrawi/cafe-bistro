<?php

namespace App\Repository;

use App\Entity\ApiMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ApiMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiMenu[]    findAll()
 * @method ApiMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiMenu::class);
    }

    // /**
    //  * @return ApiMenu[] Returns an array of ApiMenu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiMenu
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
