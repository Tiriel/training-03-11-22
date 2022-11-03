<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Comment;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $users = $this->createUsers($manager);

        $books = $this->createBooks($manager);
        $this->createComments($manager, $books);

        $genres = $this->createGenres($manager);
        $this->createMovies($manager, $genres, $users);

        $manager->flush();
    }

    private function createUsers(ObjectManager $manager): array
    {
        $users = [];

        $users[] = $user = (new User())
            ->setEmail('john.doe@example.com')
            ->setBirthday(new \DateTimeImmutable('1987-05-25'))
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
        ;
        $user->setPassword($this->hasher->hashPassword($user, 'admin1234'));
        $manager->persist($user);

        $users[] = $user = (new User())
            ->setEmail('jane.doe@example.com')
            ->setBirthday(new \DateTimeImmutable('2002-05-25'))
        ;
        $user->setPassword($this->hasher->hashPassword($user, 'user1234'));
        $manager->persist($user);

        $users[] = $user = (new User())
            ->setEmail('underage.17@example.com')
            ->setBirthday(new \DateTimeImmutable('15 years ago'))
        ;
        $user->setPassword($this->hasher->hashPassword($user, 'user1234'));
        $manager->persist($user);

        $users[] = $user = (new User())
            ->setEmail('underage.13@example.com')
            ->setBirthday(new \DateTimeImmutable('10 years ago'))
        ;
        $user->setPassword($this->hasher->hashPassword($user, 'admin1234'));
        $manager->persist($user);

        return $users;
    }

    private function createBooks(ObjectManager $manager): array
    {
        $books = [];

        $book = (new Book())
            ->setTitle('1984')
            ->setIsbn('978-2072938221')
            ->setAuthor('Georges Orwell')
            ->setReleasedAt(new \DateTimeImmutable('1949-06-08'))
            ->setPrice(10.0)
        ;
        $manager->persist($book);
        $books[] = $book;

        $book = (new Book())
            ->setTitle('Foundation')
            ->setIsbn('978-2070463619')
            ->setAuthor('Isaac Azimov')
            ->setReleasedAt(new \DateTimeImmutable('1957'))
            ->setPrice(10.0)
        ;
        $manager->persist($book);
        $books[] = $book;

        $book = (new Book())
            ->setTitle('Hyperion')
            ->setIsbn('978-2266252584')
            ->setAuthor('Dan Simmons')
            ->setReleasedAt(new \DateTimeImmutable('1989'))
            ->setPrice(10.0)
        ;
        $manager->persist($book);
        $books[] = $book;

        return $books;
    }

    private function createComments(ObjectManager $manager, iterable $books)
    {
        $comment = (new Comment())
            ->setAuthor('John Doe')
            ->setMessage('Wow! Awesome read!')
            ->setPostedAt(new \DateTimeImmutable('2022-10-13'))
            ->setBook($books[0])
        ;
        $manager->persist($comment);

        $comment = (new Comment())
            ->setAuthor('Jane Doe')
            ->setMessage('I hated it. Here\'s why in seven chapters...')
            ->setPostedAt(new \DateTimeImmutable('2022-09-13'))
            ->setBook($books[0])
        ;
        $manager->persist($comment);

        $comment = (new Comment())
            ->setAuthor('Jean Dupont')
            ->setMessage('C\'était Pas mal. Un auteur à suivre!')
            ->setPostedAt(new \DateTimeImmutable('2022-09-26'))
            ->setBook($books[1])
        ;
        $manager->persist($comment);

        $comment = (new Comment())
            ->setAuthor('Dmitri Vassili')
            ->setMessage('Blah blah blah... What a piece of junk.')
            ->setPostedAt(new \DateTimeImmutable('2022-09-19'))
            ->setBook($books[1])
        ;
        $manager->persist($comment);

        $comment = (new Comment())
            ->setAuthor('Jane Doe')
            ->setMessage('I hated it. Here\'s why in seven chapters...')
            ->setPostedAt(new \DateTimeImmutable('2022-09-13'))
            ->setBook($books[2])
        ;
        $manager->persist($comment);
    }

    private function createGenres(ObjectManager $manager): array
    {
        $genres = [];
        $genres[] = $genre = (new Genre())->setName('Action');
        $manager->persist($genre);

        $genres[] = $genre = (new Genre())->setName('Adventure');
        $manager->persist($genre);

        $genres[] = $genre = (new Genre())->setName('Fantasy');
        $manager->persist($genre);

        return $genres;
    }

    private function createMovies(ObjectManager $manager, iterable $genres, iterable $users)
    {
        [$john, $jane, $under17, $under13] = $users;

        $movie = (new Movie())
            ->setTitle('Star Wars: Episode IV - A New Hope')
            ->setReleasedAt(new \DateTimeImmutable('25 May 1977'))
            ->setPoster('https://m.media-amazon.com/images/M/MV5BOTA5NjhiOTAtZWM0ZC00MWNhLThiMzEtZDFkOTk2OTU1ZDJkXkEyXkFqcGdeQXVyMTA4NDI1NTQx._V1_SX300.jpg')
            ->setCountry('United States')
            ->setPrice(10.0)
            ->setRated('PG')
            ->setOmdbId('tt0076759')
            ->setAddedBy($john)
            ->setStatus('published')
        ;
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }
        $manager->persist($movie);

        $movie = (new Movie())
            ->setTitle('Star Wars: Episode V - The Empire Strikes Back"')
            ->setReleasedAt(new \DateTimeImmutable('20 June 1980'))
            ->setPoster('https://m.media-amazon.com/images/M/MV5BYmU1NDRjNDgtMzhiMi00NjZmLTg5NGItZDNiZjU5NTU4OTE0XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SX300.jpg')
            ->setCountry('United States')
            ->setPrice(10.0)
            ->setRated('PG')
            ->setOmdbId('tt0080684')
            ->setAddedBy($john)
            ->setStatus('published')
        ;
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }
        $manager->persist($movie);

        $movie = (new Movie())
            ->setTitle('Star Wars: Episode VI - Return of the Jedi"')
            ->setReleasedAt(new \DateTimeImmutable('25 May 1983'))
            ->setPoster('https://m.media-amazon.com/images/M/MV5BOWZlMjFiYzgtMTUzNC00Y2IzLTk1NTMtZmNhMTczNTk0ODk1XkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_SX300.jpg')
            ->setCountry('United States')
            ->setPrice(10.0)
            ->setRated('PG')
            ->setOmdbId('tt0086190')
            ->setAddedBy($john)
            ->setStatus('published')
        ;
        foreach ($genres as $genre) {
            $movie->addGenre($genre);
        }
        $manager->persist($movie);

        $movie = (new Movie())
            ->setTitle('The Lion King')
            ->setReleasedAt(new \DateTimeImmutable('24 June 1994'))
            ->setPoster('https://m.media-amazon.com/images/M/MV5BYTYxNGMyZTYtMjE3MS00MzNjLWFjNmYtMDk3N2FmM2JiM2M1XkEyXkFqcGdeQXVyNjY5NDU4NzI@._V1_SX300.jpg')
            ->setCountry('United States')
            ->setPrice(10.0)
            ->setRated('G')
            ->setOmdbId('tt0110357')
            ->setAddedBy($under13)
            ->setStatus('published')
            ->addGenre($genres[1])
            ->addGenre((new Genre())->setName('Animation'))
            ->addGenre((new Genre())->setName('Drama'))
        ;
        $manager->persist($movie);

        $movie = (new Movie())
            ->setTitle('The Matrix')
            ->setReleasedAt(new \DateTimeImmutable('31 Mars 1999'))
            ->setPoster('https://m.media-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg')
            ->setCountry('United States, Australia')
            ->setPrice(10.0)
            ->setRated('R')
            ->setOmdbId('tt0133093')
            ->setAddedBy($jane)
            ->setStatus('published')
            ->addGenre($genres[0])
            ->addGenre((new Genre())->setName('Sci-Fi'))
        ;
        $manager->persist($movie);
    }
}