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
     * Call API method through gateway
     *
     * @return mixed
     * @throws Client\Exception
     */
    public function send(Request $request)
    {
        if(null === $this->getGateway()) {
            throw new Client\Exception("Gateway is not set");
        }
        $response = $this->getGateway()->send($request);
        return $response;
    }

}
