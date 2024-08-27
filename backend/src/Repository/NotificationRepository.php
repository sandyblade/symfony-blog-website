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

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function Create(User $user, string $subject, string $message)
    {
        $notif = new Notification();
        $notif->setUser($user);
        $notif->setSubject($subject);
        $notif->setMessage($message);
        $this->getEntityManager()->persist($notif);
        $this->getEntityManager()->flush();
    }

    public function findListByUser(array $params, User $user) : array
    {
        $page       = array_key_exists("page", $params) ?  $params["page"] : 1;
        $limit      = array_key_exists("limit", $params) ?  $params["limit"] : 10;
        $search     = array_key_exists("search", $params) ?  $params["search"] : null;
        $order_by   = array_key_exists("order_by", $params) ?  $params["order_by"] : 0;
        $order_sort = array_key_exists("order_sort", $params) ?  $params["order_sort"] : "desc";
        $offset     = (($page-1) * $limit);

        $columns = [
            'x.id',
            'x.subject',
            'x.message',
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
            $orStatements->add($qb->expr()->like('x.subject', $qb->expr()->literal('%' . $search . '%')));
            $orStatements->add($qb->expr()->like('x.message', $qb->expr()->literal('%' . $search . '%')));
            $qb->andWhere($orStatements);
        }

        $qb->addOrderBy($columns[$order_by], $order_sort);
        $qb->from(Notification::class, 'x');
        return $qb->getQuery()->getResult();
    }   

    public function findNotifByUser(User $user, int $id) : ?Notification
    {
        return $this->createQueryBuilder('x')
            ->andWhere('x.user = :user AND x.id = :id')
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
