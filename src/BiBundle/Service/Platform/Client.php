<?php
/**
 * @package   BiBundle/Service/Platform
 */

namespace BiBundle\Service\Platform;
/**
 *
 * Client sends requests through different gateways
 *
 */
class Client
{
    /**
     * @var Gateway\IGateway
     */
    protected $gateway;
    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @param \BiBundle\Service\Platform\Gateway\IGateway $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return \BiBundle\Service\Platform\Gateway\IGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param Gateway\IGateway $gateway
     */
    public function __construct(Gateway\IGateway $gateway = null)
    {
        if(null !== $gateway) {
            $this->setGateway($gateway);
        }
    }
    /**
     * Send message through gateway
     *
     * @param Message $message
     * @return mixed
     * @throws Client\Exception
     */
    public function send(Message $message)
    {
        if(null === $this->getGateway()) {
            throw new Client\Exception("Message gateway is not set");
        }
        if (!$this->isEnabled()) {
            return false;
        }
        return $this->getGateway()->send($message);
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
}
