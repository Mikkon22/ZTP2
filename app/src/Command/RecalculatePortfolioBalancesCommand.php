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

use App\Entity\Portfolio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to recalculate all portfolio balances based on their transactions.
 */
#[AsCommand(
    name: 'app:recalculate-portfolio-balances',
    description: 'Recalculates all portfolio balances based on their transactions',
)]
class RecalculatePortfolioBalancesCommand extends Command
{
    /**
     * Constructor.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    /**
     * Executes the command to recalculate portfolio balances.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $portfolios = $this->entityManager->getRepository(Portfolio::class)->findAll();
        $count = 0;

        foreach ($portfolios as $portfolio) {
            $oldBalance = $portfolio->getBalance();
            $portfolio->recalculateBalance();
            $newBalance = $portfolio->getBalance();

            if ($oldBalance !== $newBalance) {
                ++$count;
                $io->text(\sprintf(
                    'Updated portfolio "%s" balance from %.2f to %.2f',
                    $portfolio->getName(),
                    $oldBalance,
                    $newBalance
                ));
            }
        }

        $this->entityManager->flush();

        if ($count > 0) {
            $io->success(\sprintf('Successfully updated %d portfolio balances.', $count));
        } else {
            $io->info('All portfolio balances are correct.');
        }

        return Command::SUCCESS;
    }
}
