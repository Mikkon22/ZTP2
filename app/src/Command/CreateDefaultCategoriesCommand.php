<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Command;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to create default expense and income categories for the admin user.
 */
#[AsCommand(
    name: 'app:create-default-categories',
    description: 'Creates default expense and income categories',
)]
class CreateDefaultCategoriesCommand extends Command
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
     * Executes the command to create default categories.
     *
     * @param InputInterface  $input  the input interface
     * @param OutputInterface $output the output interface
     *
     * @return int the command exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Creating default categories...');

        // Get the admin user
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);

        if (!$admin) {
            $output->writeln('Admin user not found. Please create an admin user first.');

            return Command::FAILURE;
        }

        // Default expense categories
        $expenseCategories = [
            ['name' => 'Jedzenie', 'color' => '#FF5733', 'type' => 'expense'],
            ['name' => 'Transport', 'color' => '#33FF57', 'type' => 'expense'],
            ['name' => 'Mieszkanie', 'color' => '#3357FF', 'type' => 'expense'],
            ['name' => 'Rozrywka', 'color' => '#FF33F5', 'type' => 'expense'],
            ['name' => 'Zakupy', 'color' => '#33FFF5', 'type' => 'expense'],
            ['name' => 'Zdrowie', 'color' => '#F5FF33', 'type' => 'expense'],
            ['name' => 'Media', 'color' => '#FF3333', 'type' => 'expense'],
        ];

        // Default income categories
        $incomeCategories = [
            ['name' => 'Wynagrodzenie', 'color' => '#33FF33', 'type' => 'income'],
            ['name' => 'Inwestycje', 'color' => '#3333FF', 'type' => 'income'],
            ['name' => 'Prezenty', 'color' => '#FF33FF', 'type' => 'income'],
            ['name' => 'Biznes poboczny', 'color' => '#FFFF33', 'type' => 'income'],
        ];

        $allCategories = array_merge($expenseCategories, $incomeCategories);

        foreach ($allCategories as $categoryData) {
            $existingCategory = $this->entityManager->getRepository(Category::class)->findOneBy([
                'name' => $categoryData['name'],
                'owner' => $admin,
            ]);

            if (!$existingCategory) {
                $category = new Category();
                $category->setName($categoryData['name']);
                $category->setColor($categoryData['color']);
                $category->setType($categoryData['type']);
                $category->setOwner($admin);

                $this->entityManager->persist($category);
                $output->writeln(\sprintf('Created category: %s', $categoryData['name']));
            } else {
                $output->writeln(\sprintf('Category already exists: %s', $categoryData['name']));
            }
        }

        $this->entityManager->flush();
        $output->writeln('Default categories created successfully!');

        return Command::SUCCESS;
    }
}
