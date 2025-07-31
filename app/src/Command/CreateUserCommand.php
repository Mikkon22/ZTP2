<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your.email@example.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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

/**
 * Command to create a new user.
 */
#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user',
)]
class CreateUserCommand extends Command
{
    /**
     * Constructor.
     *
     * @param EntityManagerInterface      $entityManager  the entity manager
     * @param UserPasswordHasherInterface $passwordHasher the password hasher
     */
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    /**
     * Configures the command arguments and options.
     */
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user');
    }

    /**
     * Executes the command to create a new user.
     *
     * @param InputInterface  $input  the input interface
     * @param OutputInterface $output the output interface
     *
     * @return int the command exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(\sprintf('User %s was created successfully!', $email));

        return Command::SUCCESS;
    }
}
