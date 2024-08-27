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

use App\Entity\Viewer;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Viewer>
 */
class ViewerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Viewer::class);
    }

    public function check(Article $article, User $user) : ?Viewer
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.user = :user AND x.article = :article')
            ->setParameter('user', $user)
            ->setParameter('article', $article)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function totalViewer(Article $article): ?int
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.article = :article')
            ->setParameter('article', $article)
            ->select('count(x.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function syncViewer(Article $article, User $user): void
    {
        $viewer = new Viewer();
        $viewer->setArticle($article);
        $viewer->setUser($user);
        $viewer->setStatus(0);
        $this->getEntityManager()->persist($viewer);
        $this->getEntityManager()->flush();

        $total_viewer = $this->totalViewer($article);
        $article->setTotalViewer($total_viewer);
        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush();
        
    }
}
