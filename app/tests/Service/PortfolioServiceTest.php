<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service;

use App\Entity\Portfolio;
use App\Entity\User;
use App\Repository\PortfolioRepository;
use App\Service\PortfolioService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PortfolioService.
 */
class PortfolioServiceTest extends TestCase
{
    private PortfolioService $service;
    private PortfolioRepository $repository;
    private EntityManagerInterface $entityManager;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(PortfolioRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->service = new PortfolioService($this->repository, $this->entityManager);
    }

    /**
     * Test create portfolio.
     */
    public function testCreatePortfolio(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setType('Test Type');
        $portfolio->setOwner($user);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($portfolio);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->createPortfolio($portfolio);
    }

    /**
     * Test update portfolio.
     */
    public function testUpdatePortfolio(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setType('Test Type');
        $portfolio->setOwner($user);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->updatePortfolio($portfolio);
    }

    /**
     * Test delete portfolio.
     */
    public function testDeletePortfolio(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setType('Test Type');
        $portfolio->setOwner($user);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($portfolio);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->deletePortfolio($portfolio);
    }
}
