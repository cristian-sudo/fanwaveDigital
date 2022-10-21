<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\League;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultMailSender
{
    public function __construct(
        private MailerInterface $mailer,
        private SerializerInterface $serializer,
    ) {
    }

    public function send(string $email, League $league): void
    {
        $email = (new TemplatedEmail())
            ->to($email)
            ->htmlTemplate('default-email.html.twig')
            ->context(['fullName' => $email,
                'id' => $league->getIdRemote(),
                'name' => $league->getName(),
                'type' => $league->getType(),
                'country' => $league->getCountry(),
                'logo' => $league->getLogo(),
        ]);

        $this->mailer->send($email);
    }
}
