<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Header\MailboxListHeader;
use Symfony\Component\Mime\Message;

final class SetGlobalEmailFromHeaderSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $mailSenderEmail)
    {
    }

    public function setEmailFromHeader(MessageEvent $event): void
    {
        $message = $event->getMessage();

        if (!$message instanceof Message) {
            return;
        }

        if ($message->getHeaders()->has('From')) {
            return;
        }

        $message->getHeaders()->add(
            new MailboxListHeader('From', Address::createArray([$this->mailSenderEmail]))
        );
    }

    public static function getSubscribedEvents()
    {
        return [MessageEvent::class => 'setEmailFromHeader'];
    }
}
