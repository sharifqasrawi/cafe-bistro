<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Item::class);
        $this->paginator = $paginator;
    }

    public function findAllByDeleted(int $value, int $page)
    {
        $dbQuery =  $this->createQueryBuilder('i')
            ->andWhere('i.IsDeleted = :val')
            ->setParameter('val', $value)
            ->addOrderBy('i.Category', 'ASC')
            ->addOrderBy('i.Title', 'ASC')
        ;

        $dbQuery->getQuery();

        $pagination = $this->paginator->paginate($dbQuery, $page, Item::perPage);

        return $pagination;
    }

    public function findAllByDeletedAndCategory(int $d, int $c, int $page)
    {
        $dbQuery =  $this->createQueryBuilder('i')
            ->andWhere('i.IsDeleted = :d')
            ->andWhere('i.Category = :c')
            ->setParameter('d', $d)
            ->setParameter('c', $c)
            ->addOrderBy('i.Category', 'ASC')
            ->addOrderBy('i.Title', 'ASC')
        ;

        $dbQuery->getQuery();

        $pagination = $this->paginator->paginate($dbQuery, $page, Item::perPage);

        return $pagination;
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
