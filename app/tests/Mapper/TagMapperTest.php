<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Mapper;

use App\DTO\TagDTO;
use App\Entity\Tag;
use App\Entity\User;
use App\Mapper\TagMapper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for TagMapper.
 */
class TagMapperTest extends TestCase
{
    private TagMapper $mapper;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->mapper = new TagMapper();
    }

    /**
     * Test entity to DTO conversion.
     */
    public function testEntityToDto(): void
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($user);

        $dto = $this->mapper->entityToDto($tag);

        $this->assertInstanceOf(TagDTO::class, $dto);
        $this->assertEquals('Test Tag', $dto->name);
    }

    /**
     * Test DTO to entity conversion with new entity.
     */
    public function testDtoToEntityWithNewEntity(): void
    {
        $dto = new TagDTO();
        $dto->name = 'New Tag';

        $tag = $this->mapper->dtoToEntity($dto);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals('New Tag', $tag->getName());
    }

    /**
     * Test update entity from DTO.
     */
    public function testUpdateEntityFromDto(): void
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $tag = new Tag();
        $tag->setName('Old Tag');
        $tag->setOwner($user);

        $dto = new TagDTO();
        $dto->name = 'Updated Tag';

        $this->mapper->updateEntityFromDto($tag, $dto);

        $this->assertEquals('Updated Tag', $tag->getName());
    }
}
