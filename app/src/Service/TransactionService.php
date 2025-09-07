<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\TransactionRepository;
use App\Service\PortfolioService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling transaction-related business logic.
 */
class TransactionService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private EntityManagerInterface $entityManager,
        private PortfolioService $portfolioService
    ) {
    }

    /**
     * Create a new transaction.
     */
    public function createTransaction(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        // Update portfolio balance
        $this->portfolioService->recalculatePortfolioBalance($transaction->getPortfolio());
    }

    /**
     * Update a transaction.
     */
    public function updateTransaction(Transaction $transaction): void
    {
        $this->entityManager->flush();

        // Update portfolio balance
        $this->portfolioService->recalculatePortfolioBalance($transaction->getPortfolio());
    }

    /**
     * Delete a transaction.
     */
    public function deleteTransaction(Transaction $transaction): void
    {
        $portfolio = $transaction->getPortfolio();
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();

        // Update portfolio balance
        $this->portfolioService->recalculatePortfolioBalance($portfolio);
    }

    /**
     * Get transactions by user with optional filters.
     */
    public function getTransactionsByUser(User $user, array $filters = []): array
    {
        return $this->transactionRepository->findByUserOptimized($user, $filters);
    }

    /**
     * Get transaction by ID and user (for security).
     */
    public function getTransactionByIdAndUser(int $id, User $user): ?Transaction
    {
        return $this->transactionRepository->createQueryBuilder('t')
            ->join('t.portfolio', 'p')
            ->where('t.id = :id')
            ->andWhere('p.owner = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get transaction statistics for user.
     */
    public function getTransactionStatistics(User $user): array
    {
        return $this->transactionRepository->getUserStatistics($user);
    }

    /**
     * Get user categories for dropdown.
     */
    public function getUserCategories(User $user): array
    {
        return $this->transactionRepository->getUserCategories($user);
    }

    /**
     * Get user portfolios for dropdown.
     */
    public function getUserPortfolios(User $user): array
    {
        return $this->transactionRepository->getUserPortfolios($user);
    }

    /**
     * Get monthly transaction summary.
     */
    public function getMonthlySummary(User $user, int $year): array
    {
        return $this->transactionRepository->getMonthlySummary($user, $year);
    }
}
