<?php

namespace App\Command;

use App\Entity\Portfolio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-portfolio-balances',
    description: 'Updates all portfolio balances based on their transactions',
)]
class UpdatePortfolioBalancesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

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
                $count++;
                $io->text(sprintf(
                    'Updated portfolio "%s" balance from %.2f to %.2f',
                    $portfolio->getName(),
                    $oldBalance,
                    $newBalance
                ));
            }
        }

        $this->entityManager->flush();

        if ($count > 0) {
            $io->success(sprintf('Successfully updated %d portfolio balances.', $count));
        } else {
            $io->info('All portfolio balances are correct.');
        }

        return Command::SUCCESS;
    }
} 