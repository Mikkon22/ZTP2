<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Data fixture for loading default categories.
 */
class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load categories into the database.
     *
     * @param ObjectManager $manager the object manager for persisting entities
     */
    public function load(ObjectManager $manager): void
    {
        /** @var User $admin */
        $admin = $this->getReference('admin-user', User::class);
        /** @var User $user */
        $user = $this->getReference('default-user', User::class);

        // Income categories
        $incomeCategories = [
            ['name' => 'Wynagrodzenie', 'color' => '#28a745', 'description' => 'Regularne wynagrodzenie'],
            ['name' => 'Projekty poboczne', 'color' => '#17a2b8', 'description' => 'Dochody z projektów pobocznych'],
            ['name' => 'Prezenty', 'color' => '#dc3545', 'description' => 'Otrzymane prezenty'],
            ['name' => 'Inne', 'color' => '#6c757d', 'description' => 'Inne źródła dochodu'],
        ];

        // Expense categories
        $expenseCategories = [
            ['name' => 'Jedzenie', 'color' => '#fd7e14', 'description' => 'Zakupy spożywcze, restauracje i dostawa jedzenia'],
            ['name' => 'Transport', 'color' => '#20c997', 'description' => 'Transport publiczny, paliwo i utrzymanie pojazdu'],
            ['name' => 'Mieszkanie', 'color' => '#6f42c1', 'description' => 'Czynsz, media i utrzymanie domu'],
            ['name' => 'Rozrywka', 'color' => '#e83e8c', 'description' => 'Filmy, gry i hobby'],
            ['name' => 'Zakupy', 'color' => '#ffc107', 'description' => 'Odzież, elektronika i przedmioty osobiste'],
            ['name' => 'Zdrowie', 'color' => '#0dcaf0', 'description' => 'Wydatki medyczne i ubezpieczenie zdrowotne'],
            ['name' => 'Edukacja', 'color' => '#198754', 'description' => 'Kursy, książki i szkolenia'],
            ['name' => 'Inne', 'color' => '#6c757d', 'description' => 'Inne wydatki'],
        ];

        // Create categories for admin user
        foreach ($incomeCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('income');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($admin);
            $manager->persist($category);
        }

        foreach ($expenseCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('expense');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($admin);
            $manager->persist($category);
        }

        // Create categories for regular user
        foreach ($incomeCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('income');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($user);
            $manager->persist($category);
        }

        foreach ($expenseCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('expense');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($user);
            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * Get the dependencies for this fixture.
     *
     * @return array the list of fixture dependencies
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
