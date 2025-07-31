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

/**
 * Command to promote a user to ROLE_ADMIN.
 */
#[AsCommand(
    name: 'app:promote-user',
    description: 'Promotes a user to ROLE_ADMIN',
)]
class PromoteUserCommand extends Command
{
    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager the entity manager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    /**
     * Configures the command arguments and options.
     */
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user to promote')
        ;
    }

    /**
     * Executes the command to promote a user to admin.
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

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error(\sprintf('User with email "%s" not found.', $email));

            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        if (\in_array('ROLE_ADMIN', $roles)) {
            $io->warning(\sprintf('User "%s" is already an admin.', $email));

            return Command::SUCCESS;
        }

        $roles[] = 'ROLE_ADMIN';
        $user->setRoles(array_unique($roles));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(\sprintf('User "%s" has been promoted to admin.', $email));

        return Command::SUCCESS;
    }
}
