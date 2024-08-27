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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
        $user       = $this->getUser();
        $user_id    = $user->getId();
        $input      = $request->getInput();
        $email      = $input->email;
        $phone      = $input->phone;
        $firstName  = $input->firstName;
        $lastName   = $input->lastName;
        $gender     = $input->gender;
        $country    = $input->country;
        $facebook   = $input->facebook;
        $instagram  = $input->instagram;
        $twitter    = $input->twitter;
        $linkedIn   = $input->linkedIn;
        $address    = $input->address;
        $aboutMe    = $input->aboutMe;
        $checkEmail = $this->em->getRepository(User::class)->findByEmail($email, $user_id);
        $checkPhone = $this->em->getRepository(User::class)->findByPhone($phone, $user_id);

        if(null !== $checkEmail)
        {
            return new JsonResponse(["message"=>"The email has already been taken.!"], 400);
        }

        if(null !== $checkPhone)
        {
            return new JsonResponse(["message"=>"The phone number has already been taken.!"], 400);
        }

        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setGender($gender);
        $user->setCountry($country);
        $user->setFacebook($facebook);
        $user->setTwitter($twitter);
        $user->setInstagram($instagram);
        $user->setLinkedIn($linkedIn);
        $user->setAddress($address);
        $user->setAboutMe($aboutMe);
        $this->em->persist($user);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Update Profile", "Edit user profile account");

        $data = $this->em->getRepository(User::class)->findByUserId($user);
        $data["fullName"] = $user->getFullName();
        $data["genderName"] = $user->getGender() == 'M' ? 'Male' : 'Female';

        return new JsonResponse(["message"=> "Your profile has been changed!!", "data"=> $data]);
    }

    #[Route('/activity', methods: ["GET"], name: 'api_account_activity')]
    public function activity(Request $request)  : JsonResponse
    {
        $user = $this->getUser();
        $request = $request->query->all();
        $data = $this->em->getRepository(Activity::class)->findListByUser($request, $user);
        return new JsonResponse($data);
    }

    #[Route('/token', methods: ["POST"], name: 'api_account_refresh_token')]
    public function token(UserInterface $user, JWTTokenManagerInterface $JWTManager)  : JsonResponse
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

    #[Route('/upload', methods: ["POST"], name: 'api_account_upload')]
    public function upload(Request $request)  : JsonResponse
    {
       
        return new JsonResponse();
    }

}
