<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface {

    public function __construct(
        private readonly bool $maintenanceMode,
        private readonly Environment $twig
    ) {
    }

    public function onResponseEvent(ResponseEvent $event): void {
        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/_')) {
            return;
        }

        if ($request->attributes->get('_route') === 'language.change') {
            return;
        }

        if (str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        if ($this->maintenanceMode) {
            $response = $event->getResponse();
            $response->setStatusCode(503);
            $response->setContent($this->twig->render('maintenance.html.twig'));
        }
    }

    public static function getSubscribedEvents(): array {
        return [
            ResponseEvent::class => 'onResponseEvent',
        ];
    }
}
