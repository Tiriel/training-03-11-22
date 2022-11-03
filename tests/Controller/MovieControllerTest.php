<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;

class MovieControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testOmdbRoutePerformances()
    {
        $client = static::createClient();
        $admin = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@example.com']);
        $client->loginUser($admin);

        $client->enableProfiler();
        $crawler = $client->request('GET', '/movie/title/1984');

        /** @var TimeDataCollector $time */
        $time = $client->getProfile()->getCollector('time');
        $this->assertLessThan(900, $time->getDuration());
    }
}