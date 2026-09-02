<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Translation\LocaleSwitcher;

final class LanguageController extends AbstractController {
    #[Route('/{locale<fr|en>}', name: 'language.change')]
    public function index(string $locale, Request $request, LocaleSwitcher $localeSwitcher,): RedirectResponse {
        $localeSwitcher->setLocale($locale);

        $request->getSession()->set('_locale', $locale);

        return $this->redirectToRoute('home.home');
    }
}
