<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task entity.
 */
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private bool $isDone = false;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    /**
     * Constructor for Task entity.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the ID of the task.
     *
     * @return int|null the task ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the task.
     *
     * @return string|null the task title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the task.
     *
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
     * Get the description of the task.
     *
     * @return string|null the task description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description of the task.
     *
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
     * Get the creation date of the task.
     *
     * @return \DateTimeImmutable|null the creation date
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date of the task.
     *
     * @param \DateTimeImmutable $createdAt the creation date to set
     *
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Check if the task is done.
     *
     * @return bool true if the task is done, false otherwise
     */
    public function isIsDone(): bool
    {
        return $this->isDone;
    }

    /**
     * Set the completion status of the task.
     *
     * @param bool $isDone the completion status to set
     *
     * @return static
     */
    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * Get the user associated with the task.
     *
     * @return User|null the user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user associated with the task.
     *
     * @param User|null $user the user to set
     *
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
