<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortfolioRepository::class)]
/**
 * Portfolio entity representing a user's financial portfolio.
 */
class Portfolio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private float $balance = 0.0;

    #[ORM\ManyToOne(inversedBy: 'portfolios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'portfolio', targetEntity: Transaction::class, orphanRemoval: true)]
    private Collection $transactions;

    /**
     * Portfolio constructor.
     */
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->balance = 0.0;
    }

    /**
     * Get the ID of the portfolio.
     *
     * @return int|null the ID of the portfolio
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name of the portfolio.
     *
     * @return string|null the name of the portfolio
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the portfolio.
     *
     * @param string $name the name to set
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the type of the portfolio.
     *
     * @return string|null the type of the portfolio
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the type of the portfolio.
     *
     * @param string $type the type to set
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the balance of the portfolio.
     *
     * @return float the balance of the portfolio
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Set the balance of the portfolio.
     *
     * @param float $balance the balance to set
     */
    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Update the balance of the portfolio based on its transactions.
     */
    public function updateBalance(): static
    {
        $this->balance = 0.0;
        foreach ($this->transactions as $transaction) {
            $this->balance += $transaction->getAmount();
        }

        return $this;
    }

    /**
     * Get the owner of the portfolio.
     *
     * @return User|null the owner of the portfolio
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Set the owner of the portfolio.
     *
     * @param User|null $owner the owner to set
     */
    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the transactions associated with the portfolio.
     *
     * @return Collection<int, Transaction> the transactions collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Add a transaction to the portfolio.
     *
     * @param Transaction $transaction the transaction to add
     */
    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setPortfolio($this);
            $this->balance += $transaction->getAmount();
        }

        return $this;
    }

    /**
     * Remove a transaction from the portfolio.
     *
     * @param Transaction $transaction the transaction to remove
     */
    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            if ($transaction->getPortfolio() === $this) {
                $transaction->setPortfolio(null);
                $this->balance -= $transaction->getAmount();
            }
        }

        return $this;
    }
}
