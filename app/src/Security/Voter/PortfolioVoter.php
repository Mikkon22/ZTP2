<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Portfolio;
    }

    /**
     * Performs a single access check operation on a given attribute, subject and token.
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
     */
    private function canAccess(Portfolio $portfolio, User $user): bool
    {
        return $portfolio->getOwner() === $user;
    }
}
