<?php

namespace App\Controller;

use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController {

    #[Route('/', name: 'home.index')]
    public function index(): RedirectResponse {
        return $this->redirectToRoute('home.home');
    }

    #[Route('/home', name: 'home.home')]
    public function home(MailService $mailService): Response {
        $mailService->sendEmail(
            to: 'thomas@tydoo.fr',
            subject: 'Hello from Symfony',
            body: $this->renderView('emails/simple.html.twig', [
                'subject' => 'Hello from Symfony',
                'content' => 'This is a test email sent from the HomeController.'
            ])
        );
        return $this->render($this->isGranted('IS_AUTHENTICATED_FULLY') ? 'home/logged.html.twig' : 'home/not-logged.html.twig');
    }
}
