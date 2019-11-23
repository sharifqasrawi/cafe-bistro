<?php

namespace App\Repository;

use App\Entity\Gallary;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Gallary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gallary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gallary[]    findAll()
 * @method Gallary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GallaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Gallary::class);
        $this->paginator = $paginator;
    }


    public function findAllPaged($page)
    {
        $dbQuery =  $this->createQueryBuilder('g')
            ->orderBy('g.Created_at', 'DESC')
        ;

        $dbQuery->getQuery();

        
        $pagination = $this->paginator->paginate($dbQuery, $page, Gallary::perPage);

        return $pagination;

    }


    // /**
    //  * @return Gallary[] Returns an array of Gallary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gallary
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
