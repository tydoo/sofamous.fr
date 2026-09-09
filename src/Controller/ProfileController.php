<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route(
        path: '/',
        name: 'profile.index',
        host: '{pseudo}.{host}',
        requirements: ['pseudo' => '[a-zA-Z0-9-]+'],
        priority: 1
    )]
    public function index(string $pseudo): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'pseudo' => $pseudo,
        ]);
    }
}
