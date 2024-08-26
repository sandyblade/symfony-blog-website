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
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByIdentifier(string $username): ?User
    {
        return $this->findByCredential($username);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByCredential($credential){
        return $this->createQueryBuilder('x')
            ->andWhere('x.email = :email AND x.confirmed = 1')
            ->setParameter('email', $credential)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmail(string $email, int $id = 0){
        return $this->createQueryBuilder('x')
            ->andWhere('x.email = :email AND x.id <> :id')
            ->setParameter('email', $email)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByPhone(string $phone, int $id = 0){
        return $this->createQueryBuilder('x')
            ->andWhere('x.phone = :phone AND x.id <> :id')
            ->setParameter('phone', $phone)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByConfirmToken(string $token){
        return $this->createQueryBuilder('x')
            ->andWhere('x.confirmToken = :token AND x.confirmed = 0')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByResetToken(string $token){
        return $this->createQueryBuilder('x')
            ->andWhere('x.resetToken = :token AND x.confirmed = 1')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
