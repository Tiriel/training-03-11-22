<?php

namespace App\Event;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

class MovieEvent extends Event
{
    public const UNDERAGE = 'movie.underage';

    public function __construct(private Movie $movie, private ?\Exception $exception = null) {}

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function getException(): ?\Exception
    {
        return $this->exception;
    }
}