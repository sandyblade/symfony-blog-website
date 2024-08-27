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
use Doctrine\ORM\EntityManagerInterface;
use App\Requests\CommentRequest;
use App\Entity\Activity;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Notification;

#[Route('api/comment')]
class CommentController extends AbstractController
{
    private EntityManagerInterface $em;
   
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list/{id}', methods: ["GET"], name: 'api_comment_list')]
    public function list(int $id)  : JsonResponse
    {
        $article = $this->em->getRepository(Article::class)->find($id);

        if(null === $article)
        {
            return new JsonResponse(["message"=>"Article with id ".$id." was not found.!!"], 400);
        }

        $data = $this->em->getRepository(Comment::class)->findByArticle($article);
        return new JsonResponse(["message"=> "ok", "data"=> $data]);
    }

    #[Route('/create/{id}', methods: ["POST"], name: 'api_comment_create')]
    public function create(int $id, CommentRequest $request)  : JsonResponse
    {
        /** @var User $user */
        $user  = $this->getUser();

        $article = $this->em->getRepository(Article::class)->find($id);

        if(null === $article)
        {
            return new JsonResponse(["message"=>"Article with id ".$id." was not found.!!"], 400);
        }

        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        $reply = $article->getUser() != $user;
        $title = $article->getTitle();
        $input = $request->getInput();
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setUser($user);
        $comment->setComment($input->comment);

        if(isset($input->parent) && null !== $input->parent)
        {
            $parent = $this->em->getRepository(Comment::class)->find($input->parent);
            $comment->setParent($parent);
        }

        $eventOrSubject = "Create Comment";
        $descriptionOrMessage = "The user ".$user->getEmail()." add to your article with title `".$title."`.";

        if($reply)
        {
            $eventOrSubject = "Reply Comment";
            $descriptionOrMessage = "The user ".$user->getEmail()." reply to your article with title `".$title."`.";
        }


        $activity = new Activity();
        $activity->setUser($user);
        $activity->setEvent($eventOrSubject);
        $activity->setDescription($descriptionOrMessage);
        $this->em->persist($activity);

        if($reply)
        {
            $notification = new Notification();
            $notification->setUser($article->getUser());
            $notification->setSubject($eventOrSubject);
            $notification->setMessage($descriptionOrMessage);
            $this->em->persist($notification);
    
        }
        
        $this->em->persist($comment);
        $this->em->flush();

        $totalComment = $this->em->getRepository(Comment::class)->totalComment($article);
        $article->setTotalComment($totalComment);
        $this->em->persist($article);
        $this->em->flush();

        $payload = [
            "id"        => $comment->getId(),
            "comment"   => $comment->getComment(),
            "createdAt" => $comment->getCreatedAt(),
            "updatedAt" => $comment->getUpdatedAt()
        ];

        return new JsonResponse(["message"=> "ok", "data"=> $payload]);
    }

    #[Route('/remove/{id}', methods: ["DELETE"], name: 'api_comment_remove')]
    public function remove(int $id)  : JsonResponse
    {
        /** @var User $user */
        $user  = $this->getUser();

        $comment = $this->em->getRepository(Comment::class)->findById($user, $id);

        if(null === $comment)
        {
            return new JsonResponse(["message"=>"Comment with id ".$id." was not found.!!"], 400);
        }

        $article = $comment->getArticle();
        $title = $article->getTitle();

        $this->em->remove($comment);
        $this->em->flush();

        $totalComment = $this->em->getRepository(Comment::class)->totalComment($article);
        $article->setTotalComment($totalComment);
        $this->em->persist($article);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Delete Comment", "The user delete article comment with title ".$title);

        return new JsonResponse(["message"=> "ok"]);

    }

}