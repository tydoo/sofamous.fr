<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService {

    private Email $email;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig
    ) {
        $this->email = new Email();
    }

    /**
     * Envois d'un email de test
     */
    public function test(): void {
        $this->email
            ->to('test@sofamous.fr')
            ->subject('Test email')
            ->html($this->twig->render('emails/default.html.twig'));

        $this->mailer->send($this->email);
    }

    public function sendEmail(string $to, string $subject, string $body): void {
        // Logic to send an email
    }
}
