<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('plainPassword', InputArgument::REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('plainPassword');

        $usernameErrors = $this->validator->validate($username, [
            new NotBlank(),
            new Length(min: 2, max: 15),
        ]);

        $plainPasswordErrors = $this->validator->validate($plainPassword, [
            new NotBlank(),
            new Length(min: 8, max: 30),
        ]);

        if (count($usernameErrors) > 0 || count($plainPasswordErrors) > 0) {
            $io->warning('Validation errors for username (min: 2, max: 15) or password (min: 8, max: 30)');

            return Command::FAILURE;
        }

        $user = (new User())
            ->setUsername($username)
        ;
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);
        $this->em->persist($user);
        $this->em->flush();

        $io->success('User created with success !');

        return Command::SUCCESS;
    }
}
