<?php

namespace App\Tests\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OmdbMovieTransformerTest extends WebTestCase
{
    /**
     * @group unit
     */
    public function testTransformerReturnsMovieInstance()
    {
        $genreMock = $this->getMockBuilder(Genre::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['getId', 'getName'])
            ->getMock();
        $genreMock->method('getId')->willReturn(1);
        $genreMock->method('getName')->willReturn('Action');

        $repositoryMock = $this->getMockBuilder(GenreRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['findOneBy'])
            ->getMock();
        $repositoryMock->expects($this->exactly(3))
            ->method('findOneBy')
            ->willReturn($this->returnCallback(function () use ($genreMock) {
                $args = func_get_args();
                if (isset($args[0]) && $args[0] === ['name' => 'Action']) {
                    return $genreMock;
                }
                return null;
            }));
        $transformer = new OmdbMovieTransformer($repositoryMock);

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
        $movie = $transformer->transform($data);

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertSame(1, $movie->getGenres()[0]->getId());
        $this->assertNull($movie->getGenres()[1]->getId());
    }

    /**
     * @group unit
     */
    public function testTransformerThrowsOnInvalidArray()
    {
        $transform = new OmdbMovieTransformer($this->createMock(GenreRepository::class));
        $this->expectException(\InvalidArgumentException::class);

        $movie = $transform->transform(['foo' => 'bar']);
    }
}