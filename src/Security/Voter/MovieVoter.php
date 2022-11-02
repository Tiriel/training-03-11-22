<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const EDIT = 'MOVIE_EDIT';
    public const VIEW = 'MOVIE_VIEW';

    public function __construct(
        private AuthorizationCheckerInterface $checker
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->checkView($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };

    }

    public function checkView(Movie $movie, User $user): bool
    {
        if ($movie->getRated() === 'G') {
            return true;
        }

        if (!$user->getBirthday()) {
            return false;
        }

        $age = $user->getBirthday()->diff(new \DateTime())->y;

        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age >= 13,
            'NC-17', 'R' => $age >= 17,
            default => false,
        };
    }

    public function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checker->isGranted('ROLE_ADMIN') ||
            ($this->checkView($movie, $user) && $movie->getAddedBy() === $user);
    }
}

