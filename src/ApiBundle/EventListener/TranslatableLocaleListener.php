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

    private $supportedLocales = ['en', 'ru'];

    public function __construct(TranslatableListener $translatableListener)
    {
        $this->translatableListener = $translatableListener;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($locale = $event->getRequest()->get('locale')) {
            if (!in_array($locale, $this->supportedLocales)) {
                throw new \ErrorException("Locale $locale is not supported");
            }
            $this->translatableListener->setTranslatableLocale($locale);
        } elseif ($locale = $event->getRequest()->getPreferredLanguage(['ru', 'en'])) {
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