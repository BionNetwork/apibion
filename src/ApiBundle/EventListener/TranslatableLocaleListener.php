<?php

namespace ApiBundle\EventListener;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class TranslatableLocaleListener
 *
 * Sets gedmo.listener.translatable locale from request headers (Accept-Language)
 */
class TranslatableLocaleListener implements EventSubscriberInterface
{
    private $translatableListener;

    public function __construct(TranslatableListener $translatableListener)
    {
        $this->translatableListener = $translatableListener;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($locale = $event->getRequest()->getPreferredLanguage(['ru', 'en'])) {
            $this->translatableListener->setTranslatableLocale($locale);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => [['onKernelRequest']],
        );
    }
}