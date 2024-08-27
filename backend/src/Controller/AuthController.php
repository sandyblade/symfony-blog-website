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
use App\Requests\RegisterRequest;
use App\Requests\ForgotPasswordRequest;
use App\Requests\ResetPasswordRequest;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Activity;

#[Route('api/auth')]
class AuthController extends AbstractController
{
    private EntityManagerInterface $em;
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(EntityManagerInterface $em, PasswordHasherFactoryInterface $hasherFactory)
    {
        $this->em = $em;
        $this->passwordHasherFactory = $hasherFactory;
    }

    #[Route('/register', methods: ["POST"], name: 'api_auth_register')]
    public function register(RegisterRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        $input = $request->getInput();
        $email = $input->email;
        $password = $input->password;
        $passwordConfirm = $input->passwordConfirm;
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $hash = $passwordHasher->hash($password);
        $faker = Factory::create();
        $token = $faker->uuid();

        if($password != $passwordConfirm)
        {
            return new JsonResponse(["message"=>"The password confirmation does not match.!"], 400);
        }

        $user = $this->em->getRepository(User::class)->findByEmail($email);

        if(null !== $user)
        {
            return new JsonResponse(["message"=>"The email has already been taken.!"], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setConfirmed(1);
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($hash);
        $user->setConfirmToken($token);
        $this->em->persist($user);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Sign Up", "Register new user account");

        return new JsonResponse(["message"=> "You need to confirm your account. We have sent you an activation code, please check your email."], 200);
    }

    #[Route('/confirm/{token}', methods: ["GET"], name: 'api_auth_confirm')]
    public function confirm(string $token): JsonResponse
    {
        $user =  $this->em->getRepository(User::class)->findByConfirmToken($token);

        if(null == $user)
        {
            return new JsonResponse(["message"=> "We can't find a user with that  token is invalid.!"], 400);
        }
        

        $user->setConfirmToken(null);
        $user->setConfirmed(1);
        $this->em->persist($user);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Email Verification", "Confirm new member registration account");

        return new JsonResponse(["message"=> "Your e-mail is verified. You can now login."]);
    }

    #[Route('/email/forgot', methods: ["POST"], name: 'api_auth_forgot')]
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        $input = $request->getInput();
        $email = $input->email;

        $user = $this->em->getRepository(User::class)->findByEmail($email);

        if(null === $user)
        {
            return new JsonResponse(["message"=>"We can't find a user with that e-mail address."], 400);
        }

        $faker = Factory::create();
        $token = $faker->uuid();

        $user->setResetToken($token);
        $this->em->persist($user);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Forgot Password", "Request reset password link");

        return new JsonResponse(["message"=>"We have e-mailed your password reset link!"], 200);
    }

    #[Route('/email/reset/{token}', methods: ["POST"], name: 'api_auth_reset')]
    public function reset(string $token, ResetPasswordRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if($errors)
        {
            return $errors;
        }

        $input = $request->getInput();
        $email = $input->email;
        $password = $input->password;
        $passwordConfirm = $input->passwordConfirm;
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $hash = $passwordHasher->hash($password);

        if($password != $passwordConfirm)
        {
            return new JsonResponse(["message"=>"The password confirmation does not match.!"], 400);
        }

        $user = $this->em->getRepository(User::class)->findByResetToken($token, $email);

        if(null === $user)
        {
            return new JsonResponse(["message"=>"We can't find a user with that e-mail address or password reset token is invalid."], 400);
        }

        $user->setPassword($hash);
        $user->setResetToken(null);
        $this->em->persist($user);
        $this->em->flush();

        $this->em->getRepository(Activity::class)->Create($user, "Reset Password", "Reset account password");

        return new JsonResponse(["message"=>"Your password has been reset!"]);
    }

}