<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
/**
 * Transaction entity representing a financial transaction.
 */
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'transaction.title.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'transaction.title.min_length',
        maxMessage: 'transaction.title.max_length'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: 'transaction.description.max_length')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'transaction.amount.not_blank')]
    #[Assert\Type(type: 'numeric', message: 'transaction.amount.type')]
    #[Assert\NotEqualTo(value: 0, message: 'transaction.amount.not_equal_to_zero')]
    private float $amount = 0.0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'transaction.date.not_blank')]
    #[Assert\Type(type: '\DateTimeInterface', message: 'transaction.date.type')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'transaction.portfolio.not_blank')]
    #[Assert\Valid]
    private ?Portfolio $portfolio = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'transaction.category.not_blank')]
    #[Assert\Valid]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'transactions')]
    private Collection $tags;

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->tags = new ArrayCollection();
    }

    /**
     * Get the ID of the transaction.
     * @return int|null the ID of the transaction
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the transaction.
     * @return string|null the title of the transaction
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the transaction.
     * @param string $title the title to set
     *
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the description of the transaction.
     * @return string|null the description of the transaction
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description of the transaction.
     * @param string|null $description the description to set
     *
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the amount of the transaction.
     * @return float the amount of the transaction
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set the amount of the transaction.
     * @param float $amount the amount to set
     *
     * @return static
     */
    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the date of the transaction.
     * @return \DateTimeInterface|null the date of the transaction
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set the date of the transaction.
     * @param \DateTimeInterface $date the date to set
     *
     * @return static
     */
    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the portfolio associated with the transaction.
     * @return Portfolio|null the portfolio entity
     */
    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    /**
     * Set the portfolio associated with the transaction.
     * @param Portfolio|null $portfolio the portfolio entity to set
     *
     * @return static
     */
    public function setPortfolio(?Portfolio $portfolio): static
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    /**
     * Get the category associated with the transaction.
     * @return Category|null the category entity
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set the category associated with the transaction.
     * @param Category|null $category the category entity to set
     *
     * @return static
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the tags associated with the transaction.
     * @return Collection<int, Tag> the tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag to the transaction.
     * @param Tag $tag the tag to add
     *
     * @return static
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove a tag from the transaction.
     * @param Tag $tag the tag to remove
     *
     * @return static
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
