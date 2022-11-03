<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;
use Symfony\Component\Security\Core\User\InMemoryUser;

class MovieControllerTest extends WebTestCase
{
    public function testOmdbRoutePerformances(): void
    {
        $client = static::createClient();
        $admin = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@example.com']);
        $client->loginUser($admin);

        $client->enableProfiler();
        $client->request('GET', '/movie/title/The Matrix');

        /** @var TimeDataCollector $time */
        $time = $client->getProfile()->getCollector('time');

        $this->assertResponseIsSuccessful();
        $this->assertLessThan(500, $time->getDuration());
    }
}
