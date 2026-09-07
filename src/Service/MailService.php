<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * Service pour l'envoi d'emails
 */
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
        $subject = 'Test email';
        $this->email
            ->to('test@sofamous.fr')
            ->subject($subject)
            ->html($this->twig->render('emails/simple.html.twig', [
                'subject' => $subject,
                'content' => 'This is a test email.'
            ]));

        $this->mailer->send($this->email);
    }

    public function sendEmail(string $to, string $subject, string $body): void {
        $this->email
            ->to($to)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($this->email);
    }
}
