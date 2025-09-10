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
     *
     * @param Tag $tag the tag entity
     *
     * @return TagDTO the tag DTO
     */
    public function entityToDto(Tag $tag): TagDTO
    {
        $dto = new TagDTO();
        $dto->name = $tag->getName();

        return $dto;
    }

    /**
     * Convert TagDTO to Tag entity.
     *
     * @param TagDTO   $dto the tag DTO
     * @param Tag|null $tag the existing tag entity
     *
     * @return Tag the tag entity
     */
    public function dtoToEntity(TagDTO $dto, ?Tag $tag = null): Tag
    {
        if (null === $tag) {
            $tag = new Tag();
        }

        $tag->setName($dto->name);

        return $tag;
    }

    /**
     * Update Tag entity with data from TagDTO.
     *
     * @param Tag    $tag the tag entity
     * @param TagDTO $dto the tag DTO
     *
     * @return Tag the updated tag entity
     */
    public function updateEntityFromDto(Tag $tag, TagDTO $dto): Tag
    {
        return $this->dtoToEntity($dto, $tag);
    }
}
