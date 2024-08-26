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


namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Entity\User;

#[AsCommand(name: 'app:seed-database')]
class SeedCommand extends Command
{
    private EntityManagerInterface $em;
    private PasswordHasherFactoryInterface $passwordHasherFactory;
    private string $defaultPassword = "P@ssw0rd!123";
    private int $maxUser = 10;

    public function __construct(EntityManagerInterface $em, PasswordHasherFactoryInterface $hasherFactory)
    {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasherFactory = $hasherFactory;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('['.date('Y-m-d H:i:s').'] Begin seed data please wait... !!');
        
        $total = (int) $this->em->getRepository(User::class)->createQueryBuilder('x')->select('count(x.id)')->getQuery()->getSingleScalarResult();
        $max = $this->maxUser;
        $passwordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $hash = $passwordHasher->hash($this->defaultPassword);

        if($total == 0)
        {
            for($i = 1; $i <= $max; $i++)
            {
                $faker = Factory::create();
                $gender = (int) rand(1, 2);
                $first_name = $gender == 1 ? $faker->firstNameMale : $faker->firstNameFemale;
                $user = new User();
                $user->setEmail($faker->safeEmail);
                $user->setPassword($hash);
                $user->setPhone($faker->phoneNumber);
                $user->setRoles(["ROLE_USER"]);
                $user->setConfirmed(1);
                $user->setFirstName($first_name);
                $user->setLastName($faker->lastName);
                $user->setGender($gender == 1 ? 'M' : 'F');
                $user->setCountry($faker->country);
                $user->setFacebook($faker->username);
                $user->setTwitter($faker->username);
                $user->setInstagram($faker->username);
                $user->setLinkedIn($faker->username);
                $user->setAddress($faker->streetAddress);
                $user->setAboutMe($this->LoremIpsum());
                $this->em->persist($user);
            }

            $this->em->flush();
        }

        $output->writeln('['.date('Y-m-d H:i:s').'] Seed data has been finished... !!');
        return Command::SUCCESS;
    }

    private function LoremIpsum()
    {
        $word =  "
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's 
            standard dummy text ever since the 1500s,  when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
            It has survived not only five centuries, 
            but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the 
            release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software 
            like Aldus PageMaker including versions of Lorem Ipsum.
        ";
        return trim($word);
    }

}