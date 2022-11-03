<?php

namespace App\Tests\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Transformer\OmdbMovieTransformer;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class OmdbMovieTransformerTest extends TestCase
{
    public function testReverseTransformThrows()
    {
        $mockRepository = $this->createMock(GenreRepository::class);
        $transformer = new OmdbMovieTransformer($mockRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Method not implemented.');
        $transformer->reverseTransform([]);
    }

    public function testGenreAreTakenFromDatabaseWhenTheyExist()
    {
        $data = [
            'Title' => 'Star Wars: Episode IV - A New Hope',
            'Year' => '1977',
            'Rated' => 'PG',
            'Released' => '25 May 1977',
            'Genre' => 'Action, Adventure, Fantasy',
            'Country' => 'United States',
            'Poster' => 'https://m.media-amazon.com/images/M/MV5BOTA5NjhiOTAtZWM0ZC00MWNhLThiMzEtZDFkOTk2OTU1ZDJkXkEyXkFqcGdeQXVyMTA4NDI1NTQx._V1_SX300.jpg',
            'imdbID' => 'tt0076759',
        ];

        $transformer = new OmdbMovieTransformer(
            $this->getGenreRepositoryMock(
                $this->getGenreMock()
            )
        );

        $movie = $transformer->transform($data);
        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertSame(1, $movie->getGenres()[0]->getId());
        $this->assertNull($movie->getGenres()[1]->getId());
    }

    private function getGenreMock(): Genre
    {
        $genreMock = $this->getMockBuilder(Genre::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getId', 'getName'])
            ->getMock();
        $genreMock->method('getId')
            ->willReturn(1);
        $genreMock->method('getName')
            ->willReturn('Action');

        return $genreMock;
    }

    private function getGenreRepositoryMock(Genre $genreMock): GenreRepository
    {
        $mockRepository = $this->getMockBuilder(GenreRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['findOneBy'])
            ->getMock();
        $mockRepository->expects($this->exactly(3))
            ->method('findOneBy')
            ->willReturn($this->returnCallback(function () use ($genreMock) {
                $args = func_get_args();
                if (isset($args[0]) && $args[0] === ['name' => 'Action']) {
                    return $genreMock;
                }
                return null;
            }));

        return $mockRepository;
    }
}
