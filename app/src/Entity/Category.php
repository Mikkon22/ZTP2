<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
/**
 * Category entity representing a financial category for a user.
 */
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = 'expense';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $color = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Transaction::class)]
    private Collection $transactions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * Get the ID of the category.
     *
     * @return int|null the ID of the category
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name of the category.
     *
     * @return string|null the name of the category
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the category.
     *
     * @param string $name the name to set
     *
     * @return static for method chaining
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the type of the category (income or expense).
     *
     * @return string|null the type of the category
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the type of the category.
     *
     * @param string $type the type to set (income or expense)
     *
     * @return static for method chaining
     */
    public function setType(?string $type): static
    {
        if (!\in_array($type, ['income', 'expense'])) {
            throw new \InvalidArgumentException('Invalid category type');
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Get the description of the category.
     *
     * @return string|null the description of the category
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description of the category.
     *
     * @param string|null $description the description to set
     *
     * @return static for method chaining
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the color of the category.
     *
     * @return string|null the color of the category
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Set the color of the category.
     *
     * @param string|null $color the color to set
     *
     * @return static for method chaining
     */
    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the owner of the category.
     *
     * @return User|null the owner of the category
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Set the owner of the category.
     *
     * @param User|null $owner the owner to set
     *
     * @return static for method chaining
     */
    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the transactions associated with this category.
     *
     * @return Collection<int, Transaction> the transactions collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Add a transaction to this category.
     *
     * @param Transaction $transaction the transaction to add
     *
     * @return static for method chaining
     */
    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setCategory($this);
        }

        return $this;
    }

    /**
     * Remove a transaction from this category.
     *
     * @param Transaction $transaction the transaction to remove
     *
     * @return static for method chaining
     */
    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCategory() === $this) {
                $transaction->setCategory(null);
            }
        }

        return $this;
    }
}
