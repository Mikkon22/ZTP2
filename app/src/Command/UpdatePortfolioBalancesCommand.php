<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
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
 * Command to update all portfolio balances based on their transactions.
 */
#[AsCommand(
    name: 'app:update-portfolio-balances',
    description: 'Updates all portfolio balances based on their transactions',
)]
class UpdatePortfolioBalancesCommand extends Command
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
     * Executes the command to update portfolio balances.
     *
     * @param InputInterface  $input  the input interface
     * @param OutputInterface $output the output interface
     *
     * @return int the command exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $portfolios = $this->entityManager->getRepository(Portfolio::class)->findAll();
        $count = 0;

        foreach ($portfolios as $portfolio) {
            $oldBalance = $portfolio->getBalance();
            $portfolio->updateBalance();
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
