<?php


// src/Command/CreateAdminCommand.php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create a new admin user',
)]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Admin Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Admin password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        // Check if user already exists
        $repo = $this->entityManager->getRepository(User::class);
        if ($repo->findOneBy(['username' => $username])) {
            $output->writeln("<error>User with this email already exists!</error>");
            return Command::FAILURE;
        }

        // Create new admin user
        $user = new User();
        $user->setUsername($username);
        $user->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("<info>Admin user created successfully: $username</info>");
        return Command::SUCCESS;
    }
}
