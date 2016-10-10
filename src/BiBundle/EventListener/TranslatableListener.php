<?php


namespace BiBundle\EventListener;


use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TranslatableListener extends \Gedmo\Translatable\TranslatableListener
{
    public function postLoad(EventArgs $args)
    {
        /** @var LifecycleEventArgs $args */
        parent::postLoad($args);
        $this->loadLocale($args);
    }

    private function loadLocale(LifecycleEventArgs $args)
    {
        $ea = $this->getEventAdapter($args);
        $om = $ea->getObjectManager();
        $object = $args->getObject();
        $meta = $om->getClassMetadata(get_class($object));
        $config = $this->getConfiguration($om, $meta->name);
        if (!isset($config['fields'])) {
            return;
        }
        $locale = $this->getTranslatableLocale($object, $meta, $om);
        if (method_exists($object, 'setLocale')) {
            $object->setLocale($locale);
        }
    }
}