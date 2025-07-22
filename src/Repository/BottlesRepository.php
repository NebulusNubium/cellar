<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Bottles;
use App\Entity\Cellars;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Bottles>
 */
class BottlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bottles::class);
    }

    public function queryByUserCaves(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.cellars', 'c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.name', 'ASC')
        ;
    }

    public function findByUserCaves(User $user): array
    {
        return $this->queryByUserCaves($user)
            ->getQuery()
            ->getResult();
    }

    public function queryByCellar(Cellars $cellar)
    {
        return $this->createQueryBuilder('b')
            // join the cellar relation
            ->innerJoin('b.cellars', 'c')
            // filter to _this_ cellar
            ->andWhere('c = :cellar')
            ->setParameter('cellar', $cellar)
            ->orderBy('b.name', 'ASC')
        ;
    }

    public function findByCellar(Cellars $cellar): array
    {
        return $this->queryByCellar($cellar)
            ->getQuery()
            ->getResult();
    }
}
