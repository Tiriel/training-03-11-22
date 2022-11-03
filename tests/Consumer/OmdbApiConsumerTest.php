<?php

namespace App\Tests\Consumer;

use App\Consumer\OMDbApiConsumer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumerTest extends TestCase
{
    private const FILM_JSON = <<<EOD
{
  "Title": "Star Wars: Episode IV - A New Hope",
  "Year": "1977",
  "Rated": "PG",
  "Released": "25 May 1977",
  "Runtime": "121 min",
  "Genre": "Action, Adventure, Fantasy",
  "Director": "George Lucas",
  "Writer": "George Lucas",
  "Actors": "Mark Hamill, Harrison Ford, Carrie Fisher",
  "Plot": "Luke Skywalker joins forces with a Jedi Knight, a cocky pilot, a Wookiee and two droids to save the galaxy from the Empire's world-destroying battle station, while also attempting to rescue Princess Leia from the mysterious Darth ...",
  "Language": "English",
  "Country": "United States",
  "Awards": "Won 6 Oscars. 63 wins & 29 nominations total",
  "Poster": "https://m.media-amazon.com/images/M/MV5BOTA5NjhiOTAtZWM0ZC00MWNhLThiMzEtZDFkOTk2OTU1ZDJkXkEyXkFqcGdeQXVyMTA4NDI1NTQx._V1_SX300.jpg",
  "Ratings": [
    {
      "Source": "Internet Movie Database",
      "Value": "8.6/10"
    },
    {
      "Source": "Rotten Tomatoes",
      "Value": "93%"
    },
    {
      "Source": "Metacritic",
      "Value": "90/100"
    }
  ],
  "Metascore": "90",
  "imdbRating": "8.6",
  "imdbVotes": "1,350,260",
  "imdbID": "tt0076759",
  "Type": "movie",
  "DVD": "06 Dec 2005",
  "BoxOffice": "$460,998,507",
  "Production": "N/A",
  "Website": "N/A",
  "Response": "True"
}
EOD;
    private const ERROR_JSON = <<<EOD
{
  "Response": "False",
  "Error": "Movie not found!"
}
EOD;


    private static HttpClientInterface $client;
    private static OMDbApiConsumer $consumer;

    public static function setUpBeforeClass(): void
    {
        $responses = [
            new MockResponse(self::FILM_JSON),
            new MockResponse(self::ERROR_JSON)
        ];
        static::$client = new MockHttpClient($responses);
        static::$consumer = new OMDbApiConsumer(static::$client);
    }

    public function testConsumerReturnsArrayWithProperApiReturn()
    {
        $data = static::$consumer->consume('t', 'Star Wars');

        $this->assertIsArray($data);
        $this->assertArrayHasKey('Title', $data);
        $this->assertContains('Star Wars: Episode IV - A New Hope', $data);
    }

    public function testConsumerThrowsOnBadApiReturn()
    {
        $this->expectException(NotFoundHttpException::class);
        static::$consumer->consume('t', 'zxfgz');
    }

    public function testConsumerThrowsOnBadModeCall()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid mode provided for consumer : i, or t allowed, f given');
        static::$consumer->consume('f', 'zxfgz');
    }
}
