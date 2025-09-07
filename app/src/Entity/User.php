<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\Table(name: '`user`')]
/**
 * User entity representing an application user.
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Portfolio::class, orphanRemoval: true)]
    private Collection $portfolios;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Category::class, orphanRemoval: true)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Tag::class, orphanRemoval: true)]
    private Collection $tags;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->portfolios = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Get the ID of the user.
     * @return int|null the ID of the user
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the email of the user.
     * @return string|null the email of the user
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the email of the user.
     * @param string $email the email to set
     *
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the unique user identifier (email).
     * @return string the user identifier (email)
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Get the roles of the user.
     * @return array the roles of the user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Set the roles of the user.
     * @param array $roles the roles to set
     *
     * @return static
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the hashed password of the user.
     * @return string the hashed password
     */
    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    /**
     * Set the hashed password of the user.
     * @param string $password the password to set
     *
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Erase credentials (no-op).
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * Get the first name of the user.
     * @return string|null the first name of the user
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the user.
     * @param string $firstName the first name to set
     *
     * @return static
     */
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the last name of the user.
     * @return string|null the last name of the user
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the user.
     * @param string $lastName the last name to set
     *
     * @return static
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the full name of the user.
     * @return string the full name of the user
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Get the portfolios owned by the user.
     * @return Collection<int, Portfolio> the portfolios collection
     */
    public function getPortfolios(): Collection
    {
        return $this->portfolios;
    }

    /**
     * Add a portfolio to the user.
     * @param Portfolio $portfolio the portfolio to add
     *
     * @return static
     */
    public function addPortfolio(Portfolio $portfolio): static
    {
        if (!$this->portfolios->contains($portfolio)) {
            $this->portfolios->add($portfolio);
            $portfolio->setOwner($this);
        }

        return $this;
    }

    /**
     * Remove a portfolio from the user.
     * @param Portfolio $portfolio the portfolio to remove
     *
     * @return static
     */
    public function removePortfolio(Portfolio $portfolio): static
    {
        if ($this->portfolios->removeElement($portfolio)) {
            if ($portfolio->getOwner() === $this) {
                $portfolio->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * Get the categories owned by the user.
     * @return Collection<int, Category> the categories collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Add a category to the user.
     * @param Category $category the category to add
     *
     * @return static
     */
    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setOwner($this);
        }

        return $this;
    }

    /**
     * Remove a category from the user.
     * @param Category $category the category to remove
     *
     * @return static
     */
    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            if ($category->getOwner() === $this) {
                $category->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * Get the tags owned by the user.
     * @return Collection<int, Tag> the tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag to the user.
     * @param Tag $tag the tag to add
     *
     * @return static
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setOwner($this);
        }

        return $this;
    }

    /**
     * Remove a tag from the user.
     * @param Tag $tag the tag to remove
     *
     * @return static
     */
    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            if ($tag->getOwner() === $this) {
                $tag->setOwner(null);
            }
        }

        return $this;
    }
}
