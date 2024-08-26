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

 
namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: "notifications", options:["engine"=> "InnoDB"])]
#[ORM\Index(columns: ["user_id"])]
#[ORM\Index(columns: ["subject"])]
#[ORM\Index(columns: ["created_at"])]
#[ORM\Index(columns: ["updated_at"])]
class Notification
{
    public function __construct() 
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', options: ["unsigned" => true])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $message;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private DateTime $updatedAt;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of user
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of subject
     *
     * @return ?string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @param ?string $subject
     *
     * @return self
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of message
     *
     * @return ?string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param ?string $message
     *
     * @return self
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
