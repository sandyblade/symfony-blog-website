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
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Requests\ChangePasswordRequest;
use App\Requests\ProfileUpdateRequest;
use App\Entity\User;
use App\Entity\Activity;

#[Route('api/account')]
class AccountController extends AbstractController
{
    private EntityManagerInterface $em;
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(EntityManagerInterface $em, PasswordHasherFactoryInterface $hasherFactory)
    {
        $this->em = $em;
        $this->passwordHasherFactory = $hasherFactory;
    }

    #[Route('/detail', methods: ["GET"], name: 'api_account_detail')]
    public function me() : JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->em->getRepository(User::class)->findByUserId($user);
        $result["fullName"] = $user->getFullName();
        $result["genderName"] = $user->getGender() == 'M' ? 'Male' : 'Female';
        return new JsonResponse($result);
    }

    #[Route('/password', methods: ["POST"], name: 'api_account_change_password')]
    public function password(ChangePasswordRequest $request) : JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        /** @var User $user */
        $user = $this->getUser();

        $input = $request->getInput();
        $currentPassword = $input->curentPassword;
        $password = $input->password;
        $passwordConfirm = $input->passwordConfirm;
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $hash = $passwordHasher->hash($password);

        if($password != $passwordConfirm)
        {
            return new JsonResponse(["message"=>"The password confirmation does not match.!"], 400);
        }
        
        $hasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $validPassword = $hasher->verify($user->getPassword(), $currentPassword);

        if(!$validPassword)
        {
            return new JsonResponse(["message"=>"Your password was not updated, since the provided current password does not match.!!"], 400);
        }

        
        $user->setPassword($hash);
        $this->em->persist($user);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Change Password", "Change new password account");

        return new JsonResponse(["message"=> "Your password has been changed!!"]);
    }

    #[Route('/update', methods: ["POST"], name: 'api_account_update')]
    public function update(ProfileUpdateRequest $request) : JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        /** @var User $user */
        $user = $this->getUser();
        $user_id = $user->getId();

        $input = $request->getInput();
        $email = $input->email;

        $checkEmail = $this->em->getRepository(User::class)->findByEmail($email, $user_id);

        if(null !== $checkEmail)
        {
            return new JsonResponse(["message"=>"The email has already been taken.!"], 400);
        }

        return new JsonResponse(["message"=> "Your profile has been changed!!"]);
    }

}