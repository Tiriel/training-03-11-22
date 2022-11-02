<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Event\MovieEvent;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/movie', name: 'app_movie_')]
class MovieController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $dispatcher) {}

    #[Route('', name: 'index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'details')]
    public function details(Movie $movie): Response
    {
        $this->denyAccessUnlessGranted(MovieVoter::VIEW, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/title/{title}', name: 'omdb')]
    public function omdb(string $title, MovieProvider $provider): Response
    {
        $movie = $provider->getMovieByTitle($title);
        $this->denyAccessUnlessGranted(MovieVoter::VIEW, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    protected function denyAccessUnlessGranted(mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attribute, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes($attribute);
            $exception->setSubject($subject);
            if (\in_array($attribute, [MovieVoter::VIEW, MovieVoter::EDIT])
                && $subject instanceof Movie
                && $this->getUser()
            ) {
                $this->dispatcher->dispatch(new MovieEvent($subject, $exception), MovieEvent::UNDERAGE);
            }

            throw $exception;
        }
    }
}
