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

class ArticleRequest extends BaseRequest
{
    #[Assert\NotBlank]
    protected string $title;

    #[Assert\NotBlank]
    protected string $description;

    #[Assert\NotBlank]
    protected string $content;

    #[Assert\NotBlank]
    protected int $status;

    protected array $categories;

    protected array $tags;

}