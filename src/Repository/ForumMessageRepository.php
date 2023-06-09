<?php

namespace App\Repository;

use App\Entity\ForumMessage;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumMessage[]    findAll()
 * @method ForumMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumMessage::class);
    }

    // /**
    //  * @return ForumMessage[] Returns an array of ForumMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumMessage
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    public function countByUser(User $user): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
