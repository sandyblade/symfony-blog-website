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

class ForgotPasswordRequest extends BaseRequest
{

    #[Assert\NotBlank]
    #[Assert\Email( message: 'The email {{ value }} is not a valid email.')]
    protected string $email;
}