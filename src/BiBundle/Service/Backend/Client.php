<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend;
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
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $method_params;

    /**
     * @param \BiBundle\Service\Backend\Gateway\IGateway $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return \BiBundle\Service\Backend\Gateway\IGateway
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
     * Call API method through gateway
     *
     * @return mixed
     * @throws Client\Exception
     */
    public function call()
    {
        if(null === $this->getGateway()) {
            throw new Client\Exception("Message gateway is not set");
        }
        return $this->getGateway()->call();
    }

}
