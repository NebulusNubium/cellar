<?php

namespace App\Repository;

use App\Entity\Cellars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cellars>
 */
class CellarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cellars::class);
    }

    public function findByNameLike(string $term): array
{
    $qb = $this->createQueryBuilder('c')
        ->join('c.user','u')
        ->where('LOWER(u.username) LIKE :q')
        ->orWhere('LOWER(c.name) LIKE :q')
        ->setParameter('q','%'.strtolower($term).'%')
        ->orderBy('u.username','ASC')
    ;
    return $qb->getQuery()->getResult();
}

    //    /**
    //     * @return Cellars[] Returns an array of Cellars objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cellars
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
