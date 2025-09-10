<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service;

use App\Entity\Portfolio;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\TransactionRepository;
use App\Service\PortfolioService;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for TransactionService.
 */
class TransactionServiceTest extends TestCase
{
    private TransactionService $service;
    private TransactionRepository $repository;
    private EntityManagerInterface $entityManager;
    private PortfolioService $portfolioService;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(TransactionRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->portfolioService = $this->createMock(PortfolioService::class);
        $this->service = new TransactionService($this->repository, $this->entityManager, $this->portfolioService);
    }

    /**
     * Test create transaction.
     */
    public function testCreateTransaction(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setOwner($user);

        $transaction = new Transaction();
        $transaction->setTitle('Test Transaction');
        $transaction->setAmount(100.50);
        $transaction->setPortfolio($portfolio);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($transaction);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->portfolioService->expects($this->once())
            ->method('recalculatePortfolioBalance')
            ->with($portfolio);

        $this->service->createTransaction($transaction);
    }

    /**
     * Test update transaction.
     */
    public function testUpdateTransaction(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setOwner($user);

        $transaction = new Transaction();
        $transaction->setTitle('Test Transaction');
        $transaction->setAmount(100.50);
        $transaction->setPortfolio($portfolio);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->portfolioService->expects($this->once())
            ->method('recalculatePortfolioBalance')
            ->with($portfolio);

        $this->service->updateTransaction($transaction);
    }

    /**
     * Test delete transaction.
     */
    public function testDeleteTransaction(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setOwner($user);

        $transaction = new Transaction();
        $transaction->setTitle('Test Transaction');
        $transaction->setAmount(100.50);
        $transaction->setPortfolio($portfolio);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($transaction);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->portfolioService->expects($this->once())
            ->method('recalculatePortfolioBalance')
            ->with($portfolio);

        $this->service->deleteTransaction($transaction);
    }
}
