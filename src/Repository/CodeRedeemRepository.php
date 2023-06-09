<?php

namespace App\Repository;

use App\Entity\CodeRedeem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CodeRedeem>
 *
 * @method CodeRedeem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeRedeem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeRedeem[]    findAll()
 * @method CodeRedeem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeRedeemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeRedeem::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CodeRedeem $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(CodeRedeem $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CodeRedeem[] Returns an array of CodeRedeem objects
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
    public function findOneBySomeField($value): ?CodeRedeem
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
