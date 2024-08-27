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

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Requests\ArticleRequest;
use App\Entity\Activity;
use App\Entity\Article;
use App\Entity\User;

#[Route('api/article')]
class ArticleController extends AbstractController
{
    private EntityManagerInterface $em;
   
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list', methods: ["GET"], name: 'api_article_list')]
    public function list(Request $request)  : JsonResponse
    {
        $request = $request->query->all();
        $data = $this->em->getRepository(Article::class)->findList($request);
        $data = array_map(function($row){
            $row["json_categories"] = json_decode($row["categories"]);
            $row["json_tags"] = json_decode($row["tags"]);
            return $row;
        }, $data);
        return new JsonResponse($data);
    }

    #[Route('/create', methods: ["POST"], name: 'api_article_create')]
    public function create(ArticleRequest $request) : JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        /** @var User $user */
        $user           = $this->getUser();
        $input          = $request->getInput();
        $title          = $input->title;
        $description    = $input->description;
        $content        = $input->content;
        $status         = $input->status;
        $categories     = $input->categories;
        $tags           = $input->tags;

        $checkTitle = $this->em->getRepository(Article::class)->findByTitle($title);

        if(null !== $checkTitle)
        {
            return new JsonResponse(["message"=>"The title has already been taken.!"], 400);
        }

        $article = new Article();
        $article->setUser($user);
        $article->setTitle($title);
        $article->setSlug($this->slugify($title));
        $article->setDescription($description);
        $article->setContent($content);
        $article->setCategories($categories ? json_encode($categories) : json_encode([]));
        $article->setTags($tags ? json_encode($tags) : json_encode([]));
        $article->setStatus($status);
        $this->em->persist($article);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Create New Article", "A new article with title `".$title."` has been created. ");

        $author = $this->em->getRepository(User::class)->findByUserId($user);

        $payload = [
            "id"            => $article->getId(),
            "title"         => $title,
            "description"   => $description,
            "categories"    => $categories,
            "tags"          => $tags,
            "status"        => $status,
            "content"       => $content,
            "createdAt"     => $article->getCreatedAt(),
            "updatedAt"     => $article->getUpdatedAt(),
            "author"        => $author
        ];

        return new JsonResponse(["message"=> "ok", "data"=> $payload]);
    }

    #[Route('/read/{slug}', methods: ["GET"], name: 'api_article_read')]
    public function read(string $slug) : JsonResponse
    {
        $article = $this->em->getRepository(Article::class)->findBySlug($slug);

        if(null === $article)
        {
            return new JsonResponse(["message"=>"Article with slug ".$slug." was not found.!!"], 400);
        }

        $author = $this->em->getRepository(User::class)->findByUserId($article->getUser());

        $payload = [
            "id"            => $article->getId(),
            "title"         => $article->getTitle(),
            "description"   => $article->getDescription(),
            "categories"    => json_decode($article->getCategories()),
            "tags"          => json_decode($article->getTags()),
            "status"        => $article->getStatus(),
            "content"       => $article->getContent(),
            "createdAt"     => $article->getCreatedAt(),
            "updatedAt"     => $article->getUpdatedAt(),
            "author"        => $author
        ];

        return new JsonResponse(["message"=> "ok", "data"=> $payload]);
    }

    #[Route('/update/{id}', methods: ["PUT"], name: 'api_article_update')]
    public function update(int $id, ArticleRequest $request) : JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        /** @var User $user */
        $user           = $this->getUser();
        $input          = $request->getInput();
        $title          = $input->title;
        $description    = $input->description;
        $content        = $input->content;
        $status         = $input->status;
        $categories     = $input->categories;
        $tags           = $input->tags;

        $checkTitle = $this->em->getRepository(Article::class)->findByTitle($title, $id);

        if(null !== $checkTitle)
        {
            return new JsonResponse(["message"=>"The title has already been taken.!"], 400);
        }

        $article = $this->em->getRepository(Article::class)->findById($user, $id);

        if(null === $article)
        {
            return new JsonResponse(["message"=>"Article with id ".$id." was not found.!!"], 400);
        }

        $article->setTitle($title);
        $article->setSlug($this->slugify($title));
        $article->setDescription($description);
        $article->setContent($content);
        $article->setCategories($categories ? json_encode($categories) : json_encode([]));
        $article->setTags($tags ? json_encode($tags) : json_encode([]));
        $article->setStatus($status);
        $this->em->persist($article);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Update Article", "The user editing article with title ".$title);

        $author = $this->em->getRepository(User::class)->findByUserId($user);

        $payload = [
            "id"            => $article->getId(),
            "title"         => $title,
            "description"   => $description,
            "categories"    => $categories,
            "tags"          => $tags,
            "status"        => $status,
            "content"       => $content,
            "createdAt"     => $article->getCreatedAt(),
            "updatedAt"     => $article->getUpdatedAt(),
            "author"        => $author
        ];

        return new JsonResponse(["message"=> "ok", "data"=> $payload]);
    }

    #[Route('/remove/{id}', methods: ["DELETE"], name: 'api_article_delete')]
    public function remove(int $id) : JsonResponse
    {
        /** @var User $user */
        $user  = $this->getUser();

        $article = $this->em->getRepository(Article::class)->findById($user, $id);

        if(null === $article)
        {
            return new JsonResponse(["message"=>"Article with id ".$id." was not found.!!"], 400);
        }

        $title = $article->getTitle();
        $this->em->remove($article);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Delete Article", "The user delete article with title ".$title);

        return new JsonResponse(["message"=> "ok"]);

    }

    #[Route('/user', methods: ["GET"], name: 'api_article_user')]
    public function user(Request $request)  : JsonResponse
    {
        /** @var User $user */
        $user  = $this->getUser();
        $request = $request->query->all();
        $data = $this->em->getRepository(Article::class)->findListUser($request, $user);
        $data = array_map(function($row){
            $row["json_categories"] = json_decode($row["categories"]);
            $row["json_tags"] = json_decode($row["tags"]);
            return $row;
        }, $data);
        return new JsonResponse($data);
    }

    #[Route('/words', methods: ["GET"], name: 'api_article_words')]
    public function words(Request $request) : JsonResponse
    {
        $result = [];
        $params = $request->query->all();
        $max = array_key_exists("max", $params) ?  $params["max"] : 100;

        for($i = 1; $i <= $max; $i++)
        {
            $faker = Factory::create();
            $result[] = ucfirst($faker->word." ".$faker->word);
        }

        sort($result);

        return new JsonResponse($result);
    }

    #[Route('/upload/{id}', methods: ["POST"], name: 'api_article_uploads')]
    public function upload(int $id, Request $request) : JsonResponse
    {
        /** @var User $user */
        $user  = $this->getUser();

        $article = $this->em->getRepository(Article::class)->findById($user, $id);

        if(null === $article)
        {
            return new JsonResponse(["message"=>"Article with id ".$id." was not found.!!"], 400);
        }

        $image = $article->getImage();

        if($request->files->get('file_image'))
        {
            $uploadPath = $this->getParameter('kernel.project_dir') . '/public/uploads'; 
            if(!is_dir($uploadPath)){
                @mkdir($uploadPath);
            }

            if(!is_null($article->getImage())){
                $file_path_current = $this->getParameter('kernel.project_dir') . '/public/'.$article->getImage(); 
                if(file_exists($file_path_current)){
                    @unlink($file_path_current);
                }
            }

            $file = $request->files->get('file_image');
            $newFileName = md5(uniqid()) . '.' . $file->guessExtension();
            $move = $file->move($uploadPath, $newFileName);

            if($move)
            {
                $article->setImage("uploads/".$newFileName);
                $this->em->persist($article);
                $this->em->flush();
                $this->em->getRepository(Activity::class)->Create($user, "Upload Image", "Upload article image");
                $image = $article->getImage(); 
            }

        }

        return new JsonResponse(["message"=> "Your article image has been uploaded", "data"=> $image]);
    }

    private function slugify($text, string $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

}