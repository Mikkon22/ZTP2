<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling tag-related business logic.
 */
class TagService
{
    public function __construct(
        private TagRepository $tagRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Create a new tag.
     */
    public function createTag(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * Update a tag.
     */
    public function updateTag(Tag $tag): void
    {
        $this->entityManager->flush();
    }

    /**
     * Delete a tag.
     */
    public function deleteTag(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    /**
     * Get tags by user.
     */
    public function getTagsByUser(User $user): array
    {
        return $this->tagRepository->findBy(['owner' => $user]);
    }

    /**
     * Get tag by ID and user (for security).
     */
    public function getTagByIdAndUser(int $id, User $user): ?Tag
    {
        return $this->tagRepository->findOneBy(['id' => $id, 'owner' => $user]);
    }
}
