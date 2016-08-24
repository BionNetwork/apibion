<?php
/**
 * @package   BiBundle/Service/Platform
 */

namespace BiBundle\Service\Platform\Queue;

use BiBundle\Service\Platform;

class StrategyQueue
{
    /**
     * Platform gateway
     *
     * @var Platform\Gateway\AbstractGateway
     */
    protected $gateway;
    /**
     * Client interface
     *
     * @var Platform\Client
     */
    protected $client;

    /**
     * @return \BiBundle\Service\Platform\Client
     */
    public function getClient()
    {
        if(null === $this->client) {
            $this->client = new Platform\Client($this->getGateway());
        }
        return $this->client;
    }
    /**
     * @param \BiBundle\Service\Platform\Gateway\AbstractGateway $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return \BiBundle\Service\Platform\Gateway\AbstractGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }
    /**
     * Build strategy object according to gateway
     *
     * @param \BiBundle\Service\Platform\Gateway\AbstractGateway $gateway
     */
    public function __construct(Platform\Gateway\AbstractGateway $gateway)
    {
        $this->gateway = $gateway;
    }
    /**
     * Choose valid gateway and send message through it
     *
     * @param $data
     * @return mixed
     */
    public function send($data)
    {
        $client = $this->getClient();
        $message = new Platform\Message();
        $message->setPhone($data['phone'])
            ->setText($data['text']);
        $gateway = $this->getGateway();
        $gateway->setFromArray($data);

        $message->translit = $data['use_translit'];

        return $client->send($message);
    }
}
