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

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

class ProfileUpdateRequest extends BaseRequest
{

    #[Assert\NotBlank]
    #[Assert\Email( message: 'The email {{ value }} is not a valid email.')]
    protected string $email;

    #[Assert\Type(type: ['digit'])]
    #[Assert\Length(
        min: 7,
        max: 14,
        minMessage: 'Your phone number must be at least {{ limit }} characters long',
        maxMessage: 'Your phone number cannot be longer than {{ limit }} characters',
    )]
    protected $phone;

    #[Assert\NotBlank]
    protected string $firstName;

    #[Assert\NotBlank]
    protected string $lastName;

    #[Assert\NotBlank]
    protected string $gender;

    protected string $country;

    protected string $facebook;

    protected string $instagram;

    protected string $twitter;

    protected string $linkedIn;

    protected string $jobTitle;

    protected string $address;

    protected string $aboutMe;

}