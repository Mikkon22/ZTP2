<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Portfolio;
use App\Entity\User;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling portfolio-related business logic.
 */
class PortfolioService
{
    public function __construct(
        private PortfolioRepository $portfolioRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Create a new portfolio.
     */
    public function createPortfolio(Portfolio $portfolio): void
    {
        $this->entityManager->persist($portfolio);
        $this->entityManager->flush();
    }

    /**
     * Update a portfolio.
     */
    public function updatePortfolio(Portfolio $portfolio): void
    {
        $this->entityManager->flush();
    }

    /**
     * Delete a portfolio.
     */
    public function deletePortfolio(Portfolio $portfolio): void
    {
        $this->entityManager->remove($portfolio);
        $this->entityManager->flush();
    }

    /**
     * Recalculate and update portfolio balance based on transactions.
     */
    public function recalculatePortfolioBalance(Portfolio $portfolio): void
    {
        $totalBalance = 0;
        foreach ($portfolio->getTransactions() as $transaction) {
            $totalBalance += $transaction->getAmount();
        }

        $portfolio->setBalance($totalBalance);
        $this->entityManager->flush();
    }

    /**
     * Get portfolios by user.
     */
    public function getPortfoliosByUser(User $user): array
    {
        return $this->portfolioRepository->findBy(['owner' => $user]);
    }

    /**
     * Get portfolio by ID and user (for security).
     */
    public function getPortfolioByIdAndUser(int $id, User $user): ?Portfolio
    {
        return $this->portfolioRepository->findOneBy(['id' => $id, 'owner' => $user]);
    }

    /**
     * Get portfolio statistics.
     */
    public function getPortfolioStatistics(Portfolio $portfolio): array
    {
        // TODO: Implement portfolio statistics calculation
        return [
            'total_transactions' => $portfolio->getTransactions()->count(),
            'total_balance' => 0.0, // TODO: Calculate from transactions
        ];
    }
}
