<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Category::class);
        $this->paginator = $paginator;
    }

    public function findAllByDeleted(int $value, int $page)
    {
        $dbQuery = $this->createQueryBuilder('c')
            ->andWhere('c.IsDeleted = :val')
            ->setParameter('val', $value)
            ->orderBy('c.Title', 'ASC')
        ;

        $dbQuery->getQuery();

        $pagination = $this->paginator->paginate($dbQuery, $page, Category::perPage);

        return $pagination;
    }

    public function findNotDeleted()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.IsDeleted = 0')
            ->orderBy('c.Title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findHomePageCategories()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.IsDeleted = 0')
            ->andWhere('c.IsOnHomePage = 1')
            ->orderBy('c.Title', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findTrashed()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.IsDeleted = 1')
            ->orderBy('c.Title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
