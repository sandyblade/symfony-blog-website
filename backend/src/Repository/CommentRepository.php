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

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findById(User $user, int $id) : ?Comment
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.id = :id AND x.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function totalComment(Article $article): ?int
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.article = :article')
            ->setParameter('article', $article)
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByArticle(Article $article){
        $columns = [
            'x.id',
            'us.email',
            'us.firstName',
            'us.lastName',
            'x.comment',
            'x.createdAt',
            'x.updatedAt'
        ];
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($columns);
        $qb->where("x.article = :article");
        $qb->setParameter('article', $article);
        $qb->addSelect('pr.id AS parent_id');
        $qb->addOrderBy("x.id", "ASC");
        $qb->from(Comment::class, 'x');
        $qb->leftJoin('x.user', 'us');
        $qb->leftJoin('x.parent', 'pr');
        $result = $qb->getQuery()->getResult();
        return $this->buildTree($result);
    }

    private function buildTree(array &$elements, $parent = null) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parent) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }else{
                    $element['children'] = [];
                }
                $branch[] = $element;
                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }

}
