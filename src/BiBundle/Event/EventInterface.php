<?php
/**
 * @package    BiBundle\Event
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Event;
/**
 * Interface for events
 * Each event should have name
 */
interface EventInterface
{
    /**
     * @return string
     */
    public function getName();
}