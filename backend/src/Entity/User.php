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

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users", options:["engine"=> "InnoDB"])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PHONE', fields: ['phone'])]
#[ORM\Index(columns: ["password"])]
#[ORM\Index(columns: ["phone"])]
#[ORM\Index(columns: ["image"])]
#[ORM\Index(columns: ["first_name"])]
#[ORM\Index(columns: ["last_name"])]
#[ORM\Index(columns: ["gender"])]
#[ORM\Index(columns: ["country"])]
#[ORM\Index(columns: ["facebook"])]
#[ORM\Index(columns: ["instagram"])]
#[ORM\Index(columns: ["twitter"])]
#[ORM\Index(columns: ["linked_in"])]
#[ORM\Index(columns: ["job_title"])]
#[ORM\Index(columns: ["confirm_token"])]
#[ORM\Index(columns: ["reset_token"])]
#[ORM\Index(columns: ["confirmed"])]
#[ORM\Index(columns: ["created_at"])]
#[ORM\Index(columns: ["updated_at"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(name: 'first_name', length: 191, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(name: 'last_name', length: 191, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twitter = null;

    #[ORM\Column(name: 'linked_in', length: 255, nullable: true)]
    private ?string $linkedIn = null;

    #[ORM\Column(name: 'job_title', length: 255, nullable: true)]
    private ?string $jobTitle = null;

    #[ORM\Column(type: 'text', length: 65535, nullable: true)]
    private ?string $address;

    #[ORM\Column(name: "about_me", type: 'text', length: 65535, nullable: true)]
    private ?string $aboutMe;

    #[ORM\Column(name:'confirm_token', length: 36, nullable: true)]
    private ?string $confirmToken = null;

    #[ORM\Column(name:'reset_token', length: 36, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: 'smallint', options: ["unsigned" => true, "default"=> 0])]
    private int $confirmed = 0;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    private DateTime $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of phone
     *
     * @return ?string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param ?string $phone
     *
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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
     * Get the value of firstName
     *
     * @return ?string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @param ?string $firstName
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     *
     * @return ?string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @param ?string $lastName
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of gender
     *
     * @return ?string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     *
     * @param ?string $gender
     *
     * @return self
     */
    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get the value of country
     *
     * @return ?string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @param ?string $country
     *
     * @return self
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of facebook
     *
     * @return ?string
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * Set the value of facebook
     *
     * @param ?string $facebook
     *
     * @return self
     */
    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get the value of instagram
     *
     * @return ?string
     */
    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    /**
     * Set the value of instagram
     *
     * @param ?string $instagram
     *
     * @return self
     */
    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get the value of twitter
     */ 
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set the value of twitter
     *
     * @return  self
     */ 
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get the value of linkedIn
     *
     * @return ?string
     */
    public function getLinkedIn(): ?string
    {
        return $this->linkedIn;
    }

    /**
     * Set the value of linkedIn
     *
     * @param ?string $linkedIn
     *
     * @return self
     */
    public function setLinkedIn(?string $linkedIn): self
    {
        $this->linkedIn = $linkedIn;

        return $this;
    }

    /**
     * Get the value of jobTitle
     *
     * @return ?string
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * Set the value of jobTitle
     *
     * @param ?string $jobTitle
     *
     * @return self
     */
    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return ?string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param ?string $address
     *
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of aboutMe
     *
     * @return ?string
     */
    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    /**
     * Set the value of aboutMe
     *
     * @param ?string $aboutMe
     *
     * @return self
     */
    public function setAboutMe(?string $aboutMe): self
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * Get the value of confirmToken
     *
     * @return ?string
     */
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * Set the value of confirmToken
     *
     * @param ?string $confirmToken
     *
     * @return self
     */
    public function setConfirmToken(?string $confirmToken): self
    {
        $this->confirmToken = $confirmToken;

        return $this;
    }

    /**
     * Get the value of resetToken
     *
     * @return ?string
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * Set the value of resetToken
     *
     * @param ?string $resetToken
     *
     * @return self
     */
    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * Get the value of confirmed
     *
     * @return int
     */
    public function getConfirmed(): int
    {
        return $this->confirmed;
    }

    /**
     * Set the value of confirmed
     *
     * @param int $confirmed
     *
     * @return self
     */
    public function setConfirmed(int $confirmed): self
    {
        $this->confirmed = $confirmed;

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
