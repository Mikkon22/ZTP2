<?php

namespace App\Security\Voter;

use App\Entity\Portfolio;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PortfolioVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Portfolio;
    }

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

    private function canAccess(Portfolio $portfolio, User $user): bool
    {
        return $portfolio->getOwner() === $user;
    }
}
