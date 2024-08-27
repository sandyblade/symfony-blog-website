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

class ChangePasswordRequest extends BaseRequest
{

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Your current password must be at least {{ limit }} characters long',
        maxMessage: 'Your current password cannot be longer than {{ limit }} characters',
    )]
    protected string $curentPassword;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    protected string $password;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Your password confirmation must be at least {{ limit }} characters long',
        maxMessage: 'Your password confirmation cannot be longer than {{ limit }} characters',
    )]
    protected string $passwordConfirm;
}