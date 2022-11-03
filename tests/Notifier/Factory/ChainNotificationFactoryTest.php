<?php

namespace App\Tests\Notifier\Factory;

use App\Notifier\Factory\ChainNotificationFactory;
use App\Notifier\Factory\DiscordNotificationFactory;
use App\Notifier\Factory\FirebaseNotificationFactory;
use App\Notifier\Factory\MailNotificationFactory;
use App\Notifier\Factory\NotificationFactoryInterface;
use App\Notifier\Factory\SlackNotificationFactory;
use App\Notifier\Notifications\DiscordNotification;
use App\Notifier\Notifications\FirebaseNotification;
use App\Notifier\Notifications\MailNotification;
use App\Notifier\Notifications\SlackNotification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Contracts\Service\Attribute\Required;

class ChainNotificationFactoryTest extends KernelTestCase
{
    private static iterable $factories = [];
    private static ?ChainNotificationFactory $factory = null;

    public static function setUpBeforeClass(): void
    {
        static::$factories = [
            'slack' => new SlackNotificationFactory(),
            'discord' => new DiscordNotificationFactory(),
            'mail' => new MailNotificationFactory()
        ];
        static::$factory = new ChainNotificationFactory(static::$factories);
    }

    public function testConstructConvertsTraversable()
    {
        $traversable = new \ArrayIterator(static::$factories);
        $factory = new ChainNotificationFactory($traversable);
        $prop = (new \ReflectionClass($factory))->getProperty('factories');

        $this->assertIsArray($prop->getValue($factory));
    }

    /**
     * @dataProvider provideNotificationChannels
     */
    public function testFactoryReturnsNotification(string $channel, string $className)
    {

        $notification = static::$factory->createNotification('Test subject', $channel);

        $this->assertInstanceOf($className, $notification);
        $this->assertSame('Test subject', $notification->getSubject());
    }

    public function testFactoryThrowsOnUnknownFactoryName()
    {
        $this->expectException(\RuntimeException::class);
        static::$factory->createNotification('Test subject', 'sms');
    }

    public function provideNotificationChannels(): array
    {
        return [
            'slack' => ['slack', SlackNotification::class],
            'discord' => ['discord', DiscordNotification::class],
            'mail' => ['mail', MailNotification::class]
        ];
    }
}
