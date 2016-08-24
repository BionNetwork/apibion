<?php
/**
 * @package    BiBundle\Service\Token
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Token;

use Predis\ClientInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Ramsey\Uuid\Uuid;

class Storage
{
    const KEY_TTL = 180;// seconds to live

    /**
     * @var ClientInterface
     */
    protected $client;
    /**
     * @var Session
     */
    protected $session;

    /**
     * @return ClientInterface
     * @throws ClientException
     */
    public function getClient()
    {
        if (null === $this->client) {
            throw new ClientException("Client is not set. Can't store key");
        }
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        if (null === $this->session) {
            $this->session = new Session();
        }
        return $this->session;
    }

    /**
     * Get token
     *
     * @param $key
     * @return mixed
     */
    public function getToken($key)
    {
        $session = $this->getSession();
//        if (\PHP_SESSION_ACTIVE !== session_status()) {
//            $session->start();
//        }
        if (null === $session->get($key)) {
            $session->set($key, (string)Uuid::uuid4());
        }

        return $session->get($key);
    }

    /**
     * Sets value by key
     *
     * @param $key
     * @param $value
     * @param int $ttl
     * @return \Predis\Response\Status
     * @throws ClientException
     */
    public function set($key, $value, $ttl = self::KEY_TTL)
    {
        $client = $this->getClient();

        return $client->set($key, $value, 'ex', $ttl);
    }

    /**
     * Gets key
     *
     * @param $key
     * @return string
     * @throws ClientException
     */
    public function get($key)
    {
        $client = $this->getClient();
        return $client->get($key);
    }

    /**
     * Gets keys by pattern
     *
     * @param $pattern
     * @return array
     * @throws ClientException
     */
    public function keys($pattern)
    {
        $client = $this->getClient();
        return $client->keys($pattern);
    }
    /**
     * Hash get
     *
     * @param $key
     * @param null $field
     * @return array|string
     * @throws ClientException
     */
    public function hget($key, $field = null)
    {
        $client = $this->getClient();
        if (!empty($field)) {
            return $client->hget($key, $field);
        }
        return $client->hgetall($key);
    }

    /**
     * Delete key
     *
     * @param $key
     * @return int
     * @throws ClientException
     */
    public function delete($key)
    {
        return $this->getClient()->del([$key]);
    }
}