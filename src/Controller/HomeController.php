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
    public function home(
        MailService $mailService
    ): Response {
        $mailService->test();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
