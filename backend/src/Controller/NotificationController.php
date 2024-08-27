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
use App\Entity\Activity;
use App\Entity\Notification;

#[Route('api/notification')]
class NotificationController extends AbstractController
{
    private EntityManagerInterface $em;
   
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/list', methods: ["GET"], name: 'api_notification_list')]
    public function list(Request $request)  : JsonResponse
    {
        $user = $this->getUser();
        $request = $request->query->all();
        $data = $this->em->getRepository(Notification::class)->findListByUser($request, $user);
        return new JsonResponse($data);
    }

    #[Route('/read/{id}', methods: ["GET"], name: 'api_notification_read')]
    public function read(int $id): JsonResponse
    {
        $user = $this->getUser();
        $notif = $this->em->getRepository(Notification::class)->findNotifByUser($user, $id);

        if(null === $notif)
        {
            return new JsonResponse(["message"=> "Notification with id ".$id." was not found.!!"], 400);
        }

        $this->em->getRepository(Activity::class)->Create($user, "Read Notification", "The user read notification with subject ".$notif->getSubject());
        
        $payload = [
            "subject"=> $notif->getSubject(),
            "message"=> $notif->getMessage(),
            "createdAt"=> $notif->getCreatedAt(),
            "updatedAt"=> $notif->getUpdatedAt()
        ];

        return new JsonResponse(["message"=> "ok", "data"=> $payload]);
    }

    #[Route('/remove/{id}', methods: ["DELETE"], name: 'api_notification_delete')]
    public function delete(int $id): JsonResponse
    {
        $user = $this->getUser();
        $notif = $this->em->getRepository(Notification::class)->findNotifByUser($user, $id);

        if(null === $notif)
        {
            return new JsonResponse(["message"=> "Notification with id ".$id." was not found.!!"], 400);
        }

        $this->em->remove($notif);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Delete Notification", "The user delete notification with subject ".$notif->getSubject());

        return new JsonResponse(["message"=> "ok"]);
    }

}