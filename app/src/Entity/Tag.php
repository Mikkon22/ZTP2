<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
/**
 * Tag entity representing a tag for transactions.
 */
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'tags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToMany(targetEntity: Transaction::class, mappedBy: 'tags')]
    private Collection $transactions;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * Get the ID of the tag.
     * @return int|null the ID of the tag
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name of the tag.
     * @return string|null the name of the tag
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the tag.
     * @param string $name the name to set
     *
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the owner of the tag.
     * @return User|null the owner of the tag
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Set the owner of the tag.
     * @param User|null $owner the owner to set
     *
     * @return static
     */
    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the transactions associated with the tag.
     * @return Collection<int, Transaction> the transactions collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Add a transaction to the tag.
     * @param Transaction $transaction the transaction to add
     *
     * @return static
     */
    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->addTag($this);
        }

        return $this;
    }

    /**
     * Remove a transaction from the tag.
     * @param Transaction $transaction the transaction to remove
     *
     * @return static
     */
    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            $transaction->removeTag($this);
        }

        return $this;
    }

    /**
     * String representation of the tag.
     * @return string the tag name
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
