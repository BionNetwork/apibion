<?php
/**
 * @package    BiBundle\Event
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Event;
/**
 * Events that are used in chain should be prefixed
 */
abstract class EventInChain extends Event implements EventInChainInterface
{
    protected $name;

    abstract public function getPrefix();

    public function getName()
    {
        return sprintf("%s.%s", $this->getPrefix(), $this->name);
    }

    /**
     * @return string
     */
    public function getClearName()
    {
        return $this->name;
    }
}