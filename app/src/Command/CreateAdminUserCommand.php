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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Command to create the admin user.
 */
#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates the admin user',
)]
class CreateAdminUserCommand extends Command
{
    /**
     * Constructor.
     */
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    /**
     * Executes the command to create the admin user.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Creating admin user...');

        // Check if admin user already exists
        $existingAdmin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);

        if ($existingAdmin) {
            $output->writeln('Admin user already exists.');

            return Command::SUCCESS;
        }

        // Create new admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Admin');
        $admin->setLastName('User');

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $output->writeln('Admin user created successfully!');
        $output->writeln('Email: admin@example.com');
        $output->writeln('Password: admin123');

        return Command::SUCCESS;
    }
}
