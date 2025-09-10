<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use App\Service\TagService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for TagService.
 */
class TagServiceTest extends TestCase
{
    private TagService $service;
    private TagRepository $repository;
    private EntityManagerInterface $entityManager;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(TagRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->service = new TagService($this->repository, $this->entityManager);
    }

    /**
     * Test create tag.
     */
    public function testCreateTag(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($user);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($tag);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->createTag($tag);
    }

    /**
     * Test update tag.
     */
    public function testUpdateTag(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($user);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->updateTag($tag);
    }

    /**
     * Test delete tag.
     */
    public function testDeleteTag(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($user);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($tag);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->deleteTag($tag);
    }
}
