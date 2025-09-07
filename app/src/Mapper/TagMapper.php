<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Mapper;

use App\DTO\TagDTO;
use App\Entity\Tag;

/**
 * Mapper for converting between Tag entity and TagDTO.
 */
class TagMapper
{
    /**
     * Convert Tag entity to TagDTO.
     */
    public function entityToDto(Tag $tag): TagDTO
    {
        $dto = new TagDTO();
        $dto->name = $tag->getName();
        $dto->color = $tag->getColor();

        return $dto;
    }

    /**
     * Convert TagDTO to Tag entity.
     */
    public function dtoToEntity(TagDTO $dto, ?Tag $tag = null): Tag
    {
        if (null === $tag) {
            $tag = new Tag();
        }

        $tag->setName($dto->name);
        $tag->setColor($dto->color);

        return $tag;
    }

    /**
     * Update Tag entity with data from TagDTO.
     */
    public function updateEntityFromDto(Tag $tag, TagDTO $dto): Tag
    {
        return $this->dtoToEntity($dto, $tag);
    }
}
