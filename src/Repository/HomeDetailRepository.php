<?php

namespace App\Repository;

use App\Entity\HomeDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HomeDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomeDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomeDetail[]    findAll()
 * @method HomeDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomeDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeDetail::class);
    }

    // /**
    //  * @return HomeDetail[] Returns an array of HomeDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HomeDetail
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
