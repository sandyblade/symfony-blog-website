<?php

/**
 * This file is part of the Sandy Andryanto Blog Application.
 *
 * @author     Sandy Andryanto <sandy.andryanto.blade@gmail.com>
 * @copyright  2024
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

 
namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    
    public function findListUser(array $params, User $user) : array
    {
        $page       = array_key_exists("page", $params) ?  $params["page"] : 1;
        $limit      = array_key_exists("limit", $params) ?  $params["limit"] : 10;
        $search     = array_key_exists("search", $params) ?  $params["search"] : null;
        $order_by   = array_key_exists("order_by", $params) ?  $params["order_by"] : 0;
        $order_sort = array_key_exists("order_sort", $params) ?  $params["order_sort"] : "desc";
        $offset     = (($page-1) * $limit);

        $columns = [
            'x.id',
            'x.slug',
            'x.title',
            'x.description',
            'x.tags',
            'x.categories',
            'x.totalViewer',
            'x.totalComment',
            'x.createdAt',
            'x.updatedAt'
        ];

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($columns);
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        $qb->where("x.user = :user");
        $qb->setParameter('user', $user);

        if(!is_null($search)){
            $orStatements = $qb->expr()->orX();
            $orStatements->add($qb->expr()->like('x.slug', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.title', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.description', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.tags', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.categories', $qb->expr()->literal('%' . $search . '%')));
            $qb->andWhere($orStatements);
        }

        $qb->addOrderBy($columns[$order_by], $order_sort);
        $qb->from(Article::class, 'x');
        return $qb->getQuery()->getResult();
    }

    public function findList(array $params) : array
    {
        $page       = array_key_exists("page", $params) ?  $params["page"] : 1;
        $limit      = array_key_exists("limit", $params) ?  $params["limit"] : 10;
        $search     = array_key_exists("search", $params) ?  $params["search"] : null;
        $order_by   = array_key_exists("order_by", $params) ?  $params["order_by"] : 0;
        $order_sort = array_key_exists("order_sort", $params) ?  $params["order_sort"] : "desc";
        $offset     = (($page-1) * $limit);

        $columns = [
            'x.id',
            'x.slug',
            'x.title',
            'x.description',
            'x.tags',
            'x.categories',
            'x.totalViewer',
            'x.totalComment',
            'x.createdAt',
            'x.updatedAt'
        ];

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($columns);
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        $qb->where("x.status = 1");

        if(!is_null($search)){
            $orStatements = $qb->expr()->orX();
            $orStatements->add($qb->expr()->like('x.slug', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.title', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.description', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.tags', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.categories', $qb->expr()->literal('%' . $search . '%')));
            $qb->andWhere($orStatements);
        }

        $qb->addOrderBy($columns[$order_by], $order_sort);
        $qb->from(Article::class, 'x');
        return $qb->getQuery()->getResult();
    }   

    public function findById(User $user, int $id) : ?Article
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.id = :id AND x.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findBySlug(string $slug, int $id = 0) : ?Article
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.slug = :slug AND x.id <> :id')
            ->setParameter('slug', $slug)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByTitle(string $title, int $id = 0) : ?Article
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.title = :title AND x.id <> :id')
            ->setParameter('title', $title)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }



}
