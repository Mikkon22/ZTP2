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
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling transaction-related business logic.
 */
class TransactionService
{
    /**
     * Constructor.
     *
     * @param TransactionRepository  $transactionRepository the transaction repository
     * @param EntityManagerInterface $entityManager         the entity manager
     * @param PortfolioService       $portfolioService      the portfolio service
     */
    public function __construct(private TransactionRepository $transactionRepository, private EntityManagerInterface $entityManager, private PortfolioService $portfolioService)
    {
    }

    /**
     * Create a new transaction.
     *
     * @param Transaction $transaction the transaction to create
     */
    public function createTransaction(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        // Update portfolio balance
        $this->portfolioService->recalculatePortfolioBalance($transaction->getPortfolio());
    }

    /**
     * Validate if user can create transaction in portfolio.
     *
     * @param Transaction $transaction the transaction
     * @param User        $user        the user
     *
     * @return bool true if user can create transaction
     */
    public function canCreateTransactionInPortfolio(Transaction $transaction, User $user): bool
    {
        $portfolio = $transaction->getPortfolio();

        return $portfolio && $portfolio->getOwner() === $user;
    }

    /**
     * Validate if user can edit transaction.
     *
     * @param Transaction $transaction the transaction
     * @param User        $user        the user
     *
     * @return bool true if user can edit transaction
     */
    public function canEditTransaction(Transaction $transaction, User $user): bool
    {
        $portfolio = $transaction->getPortfolio();

        return $portfolio && $portfolio->getOwner() === $user;
    }

    /**
     * Update a transaction.
     *
     * @param Transaction $transaction the transaction to update
     */
    public function updateTransaction(Transaction $transaction): void
    {
        $this->entityManager->flush();

        // Update portfolio balance
        $this->portfolioService->recalculatePortfolioBalance($transaction->getPortfolio());
    }

    /**
     * Delete a transaction.
     *
     * @param Transaction $transaction the transaction to delete
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
     *
     * @param User  $user    the user
     * @param array $filters optional filters
     *
     * @return array the transactions
     */
    public function getTransactionsByUser(User $user, array $filters = []): array
    {
        return $this->transactionRepository->findByUserOptimized($user, $filters);
    }

    /**
     * Get transaction by ID and user (for security).
     *
     * @param int  $id   the transaction ID
     * @param User $user the user
     *
     * @return Transaction|null the transaction or null
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
     *
     * @param User $user the user
     *
     * @return array the statistics
     */
    public function getTransactionStatistics(User $user): array
    {
        return $this->transactionRepository->getUserStatistics($user);
    }

    /**
     * Get user categories for dropdown.
     *
     * @param User $user the user
     *
     * @return array the categories
     */
    public function getUserCategories(User $user): array
    {
        return $this->transactionRepository->getUserCategories($user);
    }

    /**
     * Get user portfolios for dropdown.
     *
     * @param User $user the user
     *
     * @return array the user portfolios
     */
    public function getUserPortfolios(User $user): array
    {
        return $this->transactionRepository->getUserPortfolios($user);
    }

    /**
     * Get monthly transaction summary.
     *
     * @param User $user the user
     * @param int  $year the year
     *
     * @return array the monthly summary
     */
    public function getMonthlySummary(User $user, int $year): array
    {
        return $this->transactionRepository->getMonthlySummary($user, $year);
    }
}
