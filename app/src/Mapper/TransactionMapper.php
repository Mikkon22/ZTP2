<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Mapper;

use App\DTO\TransactionDTO;
use App\Entity\Transaction;

/**
 * Mapper for converting between Transaction entity and TransactionDTO.
 */
class TransactionMapper
{
    /**
     * Convert Transaction entity to TransactionDTO.
     */
    public function entityToDto(Transaction $transaction): TransactionDTO
    {
        $dto = new TransactionDTO();
        $dto->title = $transaction->getTitle();
        $dto->amount = abs($transaction->getAmount());
        $dto->date = $transaction->getDate();
        $dto->portfolio = $transaction->getPortfolio();
        $dto->category = $transaction->getCategory();
        $dto->description = $transaction->getDescription();
        $dto->tags = $transaction->getTags()->toArray();
        $dto->transactionType = $transaction->getAmount() < 0 ? 'expense' : 'income';

        return $dto;
    }

    /**
     * Convert TransactionDTO to Transaction entity.
     */
    public function dtoToEntity(TransactionDTO $dto, ?Transaction $transaction = null): Transaction
    {
        if (null === $transaction) {
            $transaction = new Transaction();
        }

        $transaction->setTitle($dto->title);
        $transaction->setAmount($dto->getSignedAmount());
        $transaction->setDate($dto->date);
        $transaction->setPortfolio($dto->portfolio);
        $transaction->setCategory($dto->category);
        $transaction->setDescription($dto->description);

        // Clear existing tags and add new ones
        $transaction->getTags()->clear();
        foreach ($dto->tags as $tag) {
            $transaction->addTag($tag);
        }

        return $transaction;
    }

    /**
     * Update Transaction entity with data from TransactionDTO.
     */
    public function updateEntityFromDto(Transaction $transaction, TransactionDTO $dto): Transaction
    {
        return $this->dtoToEntity($dto, $transaction);
    }
}
