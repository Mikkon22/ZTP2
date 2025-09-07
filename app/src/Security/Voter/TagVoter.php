<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Security\Voter;

use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Voter for Tag entity access control.
 */
class TagVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Tag;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // If user is not logged in, deny access
        if (!$user instanceof User) {
            return false;
        }

        /** @var Tag $tag */
        $tag = $subject;

        // Check if user is the owner of the tag
        if ($tag->getOwner() !== $user) {
            return false;
        }

        return match ($attribute) {
            self::VIEW, self::EDIT, self::DELETE => true,
            default => false,
        };
    }
}
