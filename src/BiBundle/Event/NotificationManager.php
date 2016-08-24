<?php
/**
 * @package    BiBundle\Event
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Event;
/**
 * Notification manager is used to trigger events
 * If listener is subscribed to specific event, it will be invoked during notify process
 * All events are handled by event dispatcher component
 */
class NotificationManager implements NotificationInterface
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * NotificationManager constructor.
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Notify all listeners about event
     *
     * @param Event $event
     * @return void
     */
    public function notify(Event $event)
    {
        $this->getEventDispatcher()->getDispatcher()->dispatch($event->getName(), $event);
    }

    /**
     * Main component in event management system
     *
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}