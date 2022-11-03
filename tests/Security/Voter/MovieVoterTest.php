<?php

namespace App\Tests\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Security\Voter\MovieVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class MovieVoterTest extends TestCase
{
    private ?MovieVoter $voter = null;
    private ?UsernamePasswordToken $token = null;

    protected function setUp(): void
    {
        if (null === $this->voter) {
            $mockChecker = $this->createMock(AuthorizationCheckerInterface::class);
            $this->voter = new MovieVoter($mockChecker);
            $this->token = new UsernamePasswordToken(new User(), 'main');
        }
    }

    public function testVoterSupportsMovieAndCorrectAttribute(): void
    {
        $vote = $this->voter->vote($this->token, new Movie(), [MovieVoter::VIEW]);

        $this->assertNotSame(VoterInterface::ACCESS_ABSTAIN, $vote);
    }
}
