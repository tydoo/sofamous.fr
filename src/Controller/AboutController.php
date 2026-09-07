<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutController extends AbstractController
{
    #[Route('/about', name: 'about.about')]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }

    #[Route('/terms', name: 'about.terms')]
    public function terms(): Response
    {
        return $this->render('about/terms.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }

    #[Route('/privacy', name: 'about.privacy')]
    public function privacy(): Response
    {
        return $this->render('about/privacy.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }
}
