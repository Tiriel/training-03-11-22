<?php

namespace App\Tests\Controller\Admin;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\DataCollector\DoctrineDataCollector;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;

class BookControllerTest extends WebTestCase
{
    public function testListBookPage()
    {
        $client = static::createClient();
        $admin = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@example.com']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/book/');
        $books = $crawler->filter('tbody > tr');

        $this->assertSame(3, $books->count());

        $author = $books->first()->filter('td')->eq(4)->text();
        $this->assertStringContainsString('Orwell', $author);
    }

    public function testNewBookPage(): void
    {
        $client = static::createClient();
        $admin = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@example.com']);
        $client->loginUser($admin);

        $client->enableProfiler();
        $client->request('GET', '/admin/book/new');
        /** @var TimeDataCollector $time */
        $time = $client->getProfile()->getCollector('time');
        $this->assertLessThan(500, $time->getDuration());

        $client->enableProfiler();
        $client->submitForm('Save', [
            'book[title]' => 'The Wheel of Time, Book1 - The Eye of the World',
            'book[isbn]' => '978-1857230765',
            'book[releasedAt]' => '1992-07-15',
            'book[author]' => 'Robert Jordan',
            'book[price]' => 5.0,
        ]);
        /** @var DoctrineDataCollector $db */
        $db = $client->getProfile()->getCollector('db');
        $client->followRedirect();
        $this->assertSame(4, $db->getQueryCount());

        $repo = static::getContainer()->get(BookRepository::class);
        $repo->remove($repo->findOneBy(['isbn' => '978-1857230765']), true);

        $this->assertResponseIsSuccessful();
    }
}
