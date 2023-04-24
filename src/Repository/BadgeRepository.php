<?php

namespace App\Repository;

use App\Entity\Badge;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badge::class);
    }

    public function findOwnedByUser(User $user)
{
    return $this->createQueryBuilder('b')
    ->innerJoin('b.users', 'u')
    ->andWhere('u = :user')
    ->setParameter('user', $user)
    ->getQuery()
    ->getResult();
}

    // /**
    //  * @return Badge[] Returns an array of Badge objects
    //  */
    // public function findUserBadgeRelation($user)
    // {
    //     return $this->createQueryBuilder('b')
    //         ->andWhere('b.exampleField = :val')
    //         ->setParameter('val', $user)
    //         ->orderBy('b.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    /*
    public function findOneBySomeField($value): ?Badge
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
