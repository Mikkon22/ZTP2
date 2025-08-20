<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Security\Voter;

use App\Entity\Portfolio;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter for Portfolio entity permissions.
 */
class PortfolioVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /**
     * Checks if the attribute and subject are supported by this voter.
     *
     * @param string $attribute The attribute being checked (e.g., 'view', 'edit', 'delete').
     * @param mixed  $subject   the subject to secure (should be a Portfolio)
     *
     * @return bool true if supported, false otherwise
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Portfolio;
    }

    /**
     * Performs a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute the attribute being checked
     * @param mixed          $subject   the subject to secure (should be a Portfolio)
     * @param TokenInterface $token     the security token
     *
     * @return bool true if access is granted, false otherwise
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Portfolio $portfolio */
        $portfolio = $subject;

        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $this->canAccess($portfolio, $user),
            default => false,
        };
    }

    /**
     * Checks if the user can access the portfolio.
     *
     * @param Portfolio $portfolio the portfolio entity
     * @param User      $user      the user entity
     *
     * @return bool true if the user can access, false otherwise
     */
    private function canAccess(Portfolio $portfolio, User $user): bool
    {
        return $portfolio->getOwner() === $user;
    }
}
