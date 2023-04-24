<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Friend;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, UserRepository $userRepository)
    {
        parent::__construct($registry, Friend::class, User::class);
        $this -> userRepository = $userRepository;
        
    }
    public function search($criteria)
    {  
        $stmt = $this->createQueryBuilder('f');

        if(!empty($criteria['query'])){
            $stmt->leftJoin('f.user1', 'u1');
            $stmt->leftJoin('f.user2', 'u2');
            if('u2.id != :current_id'){
            $stmt->where('u1.firstname LIKE :query'); 
            }elseif('u1.id != :current_id'){
            $stmt->where('u2.firstname LIKE :query');
            }   
            if('u2.id != :current_id'){        
            $stmt->orWhere('u2.lastname LIKE :query');
            }if('u1.id != :current_id'){
            $stmt->orWhere('u1.lastname LIKE :query'); 
            }
            if('u2.id != :current_id'){         
            $stmt->orWhere('u1.metier LIKE :query');
            }if('u1.id != :current_id'){
            $stmt->orWhere('u2.metier LIKE :query');  
            }if('u2.id != :current_id'){     
            $stmt->orWhere('u1.ville LIKE :query');
            }if('u1.id != :current_id'){
            $stmt->orWhere('u2.ville LIKE :query');    
            }
            if('u2.id != :current_id'){   
            $stmt->orWhere('u1.interet LIKE :query');
            }if('u1.id != :current_id'){
            $stmt->orWhere('u2.interet LIKE :query');    
            }
            if('u2.id != :current_id'){   
            $stmt->orWhere('u1.profil LIKE :query');
            }if('u1.id != :current_id'){
            $stmt->orWhere('u2.profil LIKE :query');    
            }
            if('u2.id != :current_id'){   
            $stmt->orWhere('u1.email LIKE :query');
            }if('u1.id != :current_id'){
            $stmt->orWhere('u2.email LIKE :query');    
            }            
            $stmt->setParameter('query', '%' . $criteria['query'] . '%');
    }
return $stmt->getQuery()->getResult();
        
    }
    // /**
    //  * @return Friend[] Returns an array of Friend objects
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
    public function findOneBySomeField($value): ?Friend
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
