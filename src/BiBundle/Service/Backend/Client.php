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
     * @var Gateway\GatewayInterface
     */
    protected $gateway;

    public function __construct(\BiBundle\Service\Backend\Gateway\GatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }
    /**
     * @param \BiBundle\Service\Backend\Gateway\GatewayInterface $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return \BiBundle\Service\Backend\Gateway\GatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Call API method through gateway
     *
     * @param Request $request
     * @return mixed
     */
    public function send(Request $request)
    {
        $response = $this->getGateway()->send($request);
        return $response;
    }

    public function post($request)
    {
        return $this->gateway->post($request);
    }
}
