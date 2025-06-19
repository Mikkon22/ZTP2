<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Portfolio;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:restore-data',
    description: 'Restores initial application data',
)]
class RestoreDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create user
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setFirstName('Admin');
        $user->setLastName('User');
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);

        // Create default categories
        $incomeCategories = ['Salary', 'Investment', 'Gift'];
        $expenseCategories = ['Food', 'Transport', 'Entertainment', 'Bills'];

        foreach ($incomeCategories as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setType('income');
            $category->setOwner($user);
            $this->entityManager->persist($category);
        }

        foreach ($expenseCategories as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setType('expense');
            $category->setOwner($user);
            $this->entityManager->persist($category);
        }

        // Create default portfolio
        $portfolio = new Portfolio();
        $portfolio->setName('Main Account');
        $portfolio->setOwner($user);
        $portfolio->setBalance(0);
        $this->entityManager->persist($portfolio);

        $this->entityManager->flush();

        $io->success('Initial data has been restored successfully.');

        $io->note('You can now log in with:');
        $io->listing([
            'Email: admin@example.com',
            'Password: password123',
        ]);

        return Command::SUCCESS;
    }
}
