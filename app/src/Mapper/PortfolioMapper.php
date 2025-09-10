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
     *
     * @param Portfolio $portfolio the portfolio entity
     *
     * @return PortfolioDTO the portfolio DTO
     */
    public function entityToDto(Portfolio $portfolio): PortfolioDTO
    {
        $dto = new PortfolioDTO();
        $dto->name = $portfolio->getName();
        $dto->description = $portfolio->getType();
        $dto->initialBalance = $portfolio->getBalance();

        return $dto;
    }

    /**
     * Convert PortfolioDTO to Portfolio entity.
     *
     * @param PortfolioDTO   $dto       the portfolio DTO
     * @param Portfolio|null $portfolio the existing portfolio entity
     *
     * @return Portfolio the portfolio entity
     */
    public function dtoToEntity(PortfolioDTO $dto, ?Portfolio $portfolio = null): Portfolio
    {
        if (null === $portfolio) {
            $portfolio = new Portfolio();
        }

        $portfolio->setName($dto->name);
        $portfolio->setType($dto->description);
        $portfolio->setBalance($dto->initialBalance);

        return $portfolio;
    }

    /**
     * Update Portfolio entity with data from PortfolioDTO.
     *
     * @param Portfolio    $portfolio the portfolio entity
     * @param PortfolioDTO $dto       the portfolio DTO
     *
     * @return Portfolio the updated portfolio entity
     */
    public function updateEntityFromDto(Portfolio $portfolio, PortfolioDTO $dto): Portfolio
    {
        return $this->dtoToEntity($dto, $portfolio);
    }
}
