<?php

namespace App\Repository;

use App\Entity\UserBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBlock[]    findAll()
 * @method UserBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBlock::class);
    }

    // /**
    //  * @return UserBlock[] Returns an array of UserBlock objects
    //  */
    
    // public function findByUser($value)
    // {
    //         $stmt = $this->createQueryBuilder('ub');
    //         $stmt->leftJoin('ub.user','u')
    //         ->Where('u.id = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?UserBlock
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
