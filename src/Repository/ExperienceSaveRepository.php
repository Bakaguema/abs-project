<?php

namespace App\Repository;

use App\Entity\ExperienceSave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExperienceSave|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExperienceSave|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExperienceSave[]    findAll()
 * @method ExperienceSave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienceSaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExperienceSave::class);
    }

    // /**
    //  * @return ExperienceSave[] Returns an array of ExperienceSave objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExperienceSave
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
