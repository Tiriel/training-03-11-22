<?php

namespace App\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class OmdbMovieTransformer implements DataTransformerInterface
{
    public function __construct(private GenreRepository $genreRepository)
    {}

    public function transform($value): Movie
    {
        if (!\array_key_exists('Genre', $value)) {
            throw new \InvalidArgumentException();
        }

        $genres = explode(', ', $value['Genre']);
        $date = $value['Released'] === 'N/A' ? $value['Year'] : $value['Released'];
        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setOmdbId($value['imdbID'])
            ->setRated($value['Rated'])
            ->setPrice(5.0)
        ;

        foreach ($genres as $genre) {
            $genreEnt = $this->genreRepository->findOneBy(['name' => $genre]) ?? (new Genre())
                    ->setName($genre)
                    ->setPoster($value['Poster'])
            ;
            $movie->addGenre($genreEnt);
        }

        return $movie;
    }

    public function reverseTransform(mixed $value): mixed
    {
        throw new \RuntimeException('Method not implemented.');
    }
}

