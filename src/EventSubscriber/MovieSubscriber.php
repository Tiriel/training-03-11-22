<?php

namespace App\EventSubscriber;

use App\Entity\Movie;
use App\Event\MovieEvent;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private MailerInterface $mailer
    ) {}

    public function onMovieEventUnderage(MovieEvent $event): void
    {
        if (!($movie = $event->getMovie()) instanceof Movie) {
            return;
        }
        $admins = array_values($this->userRepository->findAdminEmails());

        $mail = (new Email())
            ->from('admin@example.com')
            ->subject('New underage viewing')
            ->html($this->getHtml($movie, $this->security->getUser()))
            ->to(...$admins)
        ;
        $this->mailer->send($mail);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieEvent::UNDERAGE => 'onMovieEventUnderage',
        ];
    }

    private function getHtml(Movie $movie, UserInterface $user): string
    {
        return <<<EOD
<html lang="en">
<body>
    <h1>New underage viewing: {$movie->getTitle()}</h1>
    <div>
        <ul>
            <li>User : {$user->getUserIdentifier()}</li>
            <li>Movie : {$movie->getTitle()}</li>
            <li>Movie IMDb ID : {$movie->getOmdbId()}</li>
            <li>Rated : {$movie->getRated()}</li>
        </ul>
    </div>
</body>
</html>
EOD;
    }
}
