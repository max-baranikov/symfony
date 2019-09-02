<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.last_read', 'ASC')     // order by last_read field
            ->getQuery()
            ->useResultCache(true)              // cache result of this query
            ->setResultCacheLifetime(86400)     // cache life time is 24 hours (60*60*24)
            ->getResult()
        ;
    }
    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('b')
    ->andWhere('b.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('b.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
public function findOneBySomeField($value): ?Book
{
return $this->createQueryBuilder('b')
->andWhere('b.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}
