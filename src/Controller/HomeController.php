<?php

namespace App\Controller;

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
    public function home(): Response {
        return $this->render($this->isGranted('IS_AUTHENTICATED_FULLY') ? 'home/logged.html.twig' : 'home/not-logged.html.twig');
    }
}
