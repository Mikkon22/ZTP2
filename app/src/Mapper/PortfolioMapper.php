<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Mapper;

use App\DTO\PortfolioDTO;
use App\Entity\Portfolio;

/**
 * Mapper for converting between Portfolio entity and PortfolioDTO.
 */
class PortfolioMapper
{
    /**
     * Convert Portfolio entity to PortfolioDTO.
     */
    public function entityToDto(Portfolio $portfolio): PortfolioDTO
    {
        $dto = new PortfolioDTO();
        $dto->name = $portfolio->getName();
        $dto->description = $portfolio->getDescription();
        $dto->initialBalance = $portfolio->getBalance();
        $dto->currency = $portfolio->getCurrency();

        return $dto;
    }

    /**
     * Convert PortfolioDTO to Portfolio entity.
     */
    public function dtoToEntity(PortfolioDTO $dto, ?Portfolio $portfolio = null): Portfolio
    {
        if (null === $portfolio) {
            $portfolio = new Portfolio();
        }

        $portfolio->setName($dto->name);
        $portfolio->setDescription($dto->description);
        $portfolio->setBalance($dto->initialBalance);
        $portfolio->setCurrency($dto->currency);

        return $portfolio;
    }

    /**
     * Update Portfolio entity with data from PortfolioDTO.
     */
    public function updateEntityFromDto(Portfolio $portfolio, PortfolioDTO $dto): Portfolio
    {
        return $this->dtoToEntity($dto, $portfolio);
    }
}
