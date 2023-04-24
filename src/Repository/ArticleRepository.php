<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function search($criteria)
    {
        $stmt = $this->createQueryBuilder('a');

        if (!empty($criteria['query'])) {
            $stmt->leftJoin('a.categories', 'c');
            $stmt->leftJoin('a.users', 'u');
            $stmt->where('a.title LIKE :query');
            $stmt->orWhere('a.content LIKE :query');
            $stmt->orWhere('c.name LIKE :query');
            $stmt->orWhere('u.lastname LIKE :query');
            $stmt->setParameter('query', '%' . $criteria['query'] . '%');
        }


        return $stmt->getQuery()->getResult();
    }
    public function findArticleByPole($poles) // Renvoie les articles du pole passé en parametre classé par date du plus récent au plus ancien et actif
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.poles', 'c')
            ->addSelect('c')
            ->OrderBy('a.createdAt', 'asc')
            ->where('c.id LIKE :query')
            ->setParameter('query', $poles)
            ->andWhere('a.active = 1')
            ->getQuery()
            ->getResult();
        return $query;
    }
}
