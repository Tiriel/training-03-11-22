<?php

namespace App\Provider;

use App\Consumer\OMDbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class MovieProvider
{
    public function __construct(
        private MovieRepository $movieRepository,
        private OMDbApiConsumer $consumer,
        private OmdbMovieTransformer $transformer,
        private Security $security,
        private WorkflowInterface $movieStateMachine
    ) {}

    public function getMovieByTitle(string $title)
    {
        return $this->getOneMovie(OMDbApiConsumer::MODE_TITLE, $title);
    }

    public function getMovieById(string $id): Movie
    {
        return $this->getOneMovie(OMDbApiConsumer::MODE_ID, $id);
    }

    private function getOneMovie(string $mode, string $value)
    {
        $movie = $this->transformer->transform(
            $this->consumer->consume($mode,  $value)
        );

        if ($entity = $this->movieRepository->findOneBy(['title' => $movie->getTitle()])) {
            return $entity;
        }

        $movie->setAddedBy($this->security->getUser());
        $this->chooseTransition($movie);
        $this->movieRepository->add($movie, true);

        return $movie;
    }

    private function chooseTransition(Movie $movie): void
    {
        if ($this->movieStateMachine->can($movie, 'publish')) {
            if ($movie->getRated() === 'G') {
                $this->movieStateMachine->apply($movie, 'publish');
                return;
            }

            $this->movieStateMachine->apply($movie, 'hold');
        }
    }
}

