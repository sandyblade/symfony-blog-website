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

class CommentRequest extends BaseRequest
{
    protected ?int $parentId = null;

    #[Assert\NotBlank]
    protected string $comment;
}