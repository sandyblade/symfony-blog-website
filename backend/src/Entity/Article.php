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

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\Table(name: "articles", options:["engine"=> "InnoDB"])]
#[ORM\Index(columns: ["user_id"])]
#[ORM\Index(columns: ["image"])]
#[ORM\Index(columns: ["slug"])]
#[ORM\Index(columns: ["title"])]
#[ORM\Index(columns: ["total_viewer"])]
#[ORM\Index(columns: ["total_comment"])]
#[ORM\Index(columns: ["status"])]
#[ORM\Index(columns: ["created_at"])]
#[ORM\Index(columns: ["updated_at"])]
class Article
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
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: false)]
    private ?string $description;

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $content;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $tags;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $categories;

    #[ORM\Column(name: 'total_viewer', type: 'integer', options: ["unsigned" => true, "default"=> 0])]
    private int $totalViewer = 0;

    #[ORM\Column(name: 'total_comment', type: 'integer', options: ["unsigned" => true, "default"=> 0])]
    private int $totalComment = 0;

    #[ORM\Column(type: 'smallint', options: ["unsigned" => true, "default"=> 0])]
    private int $status = 0;

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
     * Get the value of image
     *
     * @return ?string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param ?string $image
     *
     * @return self
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of slug
     *
     * @return ?string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @param ?string $slug
     *
     * @return self
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param ?string $title
     *
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param ?string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return ?string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param ?string $content
     *
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of tags
     *
     * @return ?string
     */
    public function getTags(): ?string
    {
        return $this->tags;
    }

    /**
     * Set the value of tags
     *
     * @param ?string $tags
     *
     * @return self
     */
    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get the value of categories
     *
     * @return ?string
     */
    public function getCategories(): ?string
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     *
     * @param ?string $categories
     *
     * @return self
     */
    public function setCategories(?string $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the value of totalViewer
     *
     * @return int
     */
    public function getTotalViewer(): int
    {
        return $this->totalViewer;
    }

    /**
     * Set the value of totalViewer
     *
     * @param int $totalViewer
     *
     * @return self
     */
    public function setTotalViewer(int $totalViewer): self
    {
        $this->totalViewer = $totalViewer;

        return $this;
    }

    /**
     * Get the value of totalComment
     *
     * @return int
     */
    public function getTotalComment(): int
    {
        return $this->totalComment;
    }

    /**
     * Set the value of totalComment
     *
     * @param int $totalComment
     *
     * @return self
     */
    public function setTotalComment(int $totalComment): self
    {
        $this->totalComment = $totalComment;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param int $status
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

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
