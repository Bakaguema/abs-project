<?php

namespace App\Repository;

use App\Entity\Badge;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    private $chatRepository;


    public function __construct(ChatRepository $chatRepository, ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->chatRepository = $chatRepository;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @throws ORMException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function search($criteria, $laRegion, $leDepartement, $laville)
    {
        $stmt = $this->createQueryBuilder('u')
            ->leftjoin('u.city', 'c', 'WITH', 'c.id = u.city')
            ->leftjoin('c.departement', 'dpt', 'WITH', 'dpt.code = c.departement')
            ->leftjoin('dpt.region', 'r', 'WITH', 'r.code = dpt.region');
        if (!empty($criteria['query'])) {
            $stmt->where('u.firstname LIKE :query');
            $stmt->orWhere('u.lastname LIKE :query');
            $stmt->orWhere('u.metier LIKE :query');
            $stmt->orWhere('u.interet LIKE :query');
            $stmt->orWhere('u.profil LIKE :query');
            $stmt->orWhere('u.email LIKE :query');
            $stmt->orWhere('c.name LIKE :query');
            $stmt->orWhere('dpt.name LIKE :query');
            $stmt->orWhere('r.name LIKE :query');
            $stmt->setParameter('query', '%' . $criteria['query'] . '%');
        }
        if (!empty($laRegion) && empty($criteria['rayon'])) {

            $stmt->andWhere('r.code = :selectRegion');
            $stmt->setParameter('selectRegion', $laRegion->getCode());
        }
        if (!empty($leDepartement) && empty($criteria['rayon'])) {

            $stmt->andWhere('dpt.code = :selectDepartement');
            $stmt->setParameter('selectDepartement', $leDepartement->getCode());
        }
        if (!empty($laville)) {

            if (!empty($criteria['rayon'])) {
                $stmt->addSelect('( 6371.4 * acos(cos(radians(' . $laville->getGpsLat() . '))
                * cos( radians( c.gps_lat ) )
                * cos( radians( c.gps_lng )
                - radians(' . $laville->getGpsLng() . ') )
                + sin( radians(' . $laville->getGpsLat() . ') )
                * sin( radians( c.gps_lat ) ) ) ) as HIDDEN distance');
                $stmt->andHaving('distance <= :rayon');
                $stmt->setParameter(':rayon', $criteria['rayon']);
            } else {
                $stmt->andWhere('c.id = :selectCity');
                $stmt->setParameter('selectCity', $laville->getId());
            }
        }
        return $stmt->getQuery()->getResult();
    }

    public function findBadgeOwners(Badge $badge)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.badges', 'b')
            ->andWhere('b = :badge')
            ->setParameter('badge', $badge)
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findNotBadgeOwners(int $badgeId)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->leftJoin('u.badges', 'b')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('b.id'),
                $qb->expr()->neq('b.id', ':badgeId')
            ))
            ->setParameter('badgeId', $badgeId);

        $qb2 = $this->createQueryBuilder('u2');
        $qb2->select('u2.id')
            ->leftJoin('u2.badges', 'b2')
            ->andWhere('b2.id = :badgeId')
            ->setParameter('badgeId', $badgeId);

        $qb->andWhere($qb->expr()->notIn('u.id', $qb2->getDQL()));
        $qb->orderBy('u.lastname', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getActiveChatsUsers($currentUser): array
    {
        $chats = $this->chatRepository->findWithOneUser($currentUser);
        $query = $this->createQueryBuilder('u');

        $query->andWhere('c IN(:chats)')
            ->join('u.chats', 'c');
        $query->setParameter(':chats', array_values($chats))
            ->andWhere('u.id != :current')
            ->setParameter(':current', $currentUser);
        return $query->getQuery()
            ->getResult();
    }

    public function getTheOtherUser($currentUser, $chatId): array
    {
        $chats = $this->chatRepository->findBy(['id' => $chatId]);
        $query = $this->createQueryBuilder('u');

        $query->andWhere('c IN(:chats)')
                    ->join('u.chats', 'c');
        $query->setParameter(':chats', array_values($chats))
            ->andWhere('u.id != :current')
            ->setParameter(':current', $currentUser)
        ;
              return $query->getQuery()
               ->getResult()
           ;
    }


   
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
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
class DeletedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasField("deletedAt")) {
            $date = date("Y-m-d h:m:s");

            return $targetTableAlias . ".deletedAt < '" . $date . "' OR " . $targetTableAlias . ".deletedAt IS NULL";
        }

        return "";
    }
}