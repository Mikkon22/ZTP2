<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Security\Voter;

use App\Entity\Transaction;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter for Transaction entity access control.
 */
class TransactionVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /**
     * Check if voter supports the given attribute and subject.
     *
     * @param string $attribute the attribute to check
     * @param mixed  $subject   the subject to check
     *
     * @return bool true if supported
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Transaction;
    }

    /**
     * Vote on the given attribute and subject.
     *
     * @param string         $attribute the attribute to vote on
     * @param mixed          $subject   the subject to vote on
     * @param TokenInterface $token     the authentication token
     *
     * @return bool true if access is granted
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // If user is not logged in, deny access
        if (!$user instanceof User) {
            return false;
        }

        /** @var Transaction $transaction */
        $transaction = $subject;

        // Check if user is the owner of the portfolio that contains this transaction
        if ($transaction->getPortfolio()?->getOwner() !== $user) {
            return false;
        }

        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => true,
            default => false,
        };
    }
}
