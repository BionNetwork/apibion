<?php
/**
 * @package    BiBundle\Event
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Event;
/**
 * Event class
 * Main component for event management system
 * It can hold any information about domain
 */
class Event extends \Symfony\Component\EventDispatcher\GenericEvent implements EventInterface
{

}