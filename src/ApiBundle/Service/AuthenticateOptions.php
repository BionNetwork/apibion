<?php
/**
 * @package    ApiBundle\Service
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service;

class AuthenticateOptions implements \ArrayAccess
{
    const AUTH_KEY_TTL = 3600; // 1 hour

    protected $options;

    public function __construct(array $options = array())
    {
        if (empty($options['auth_key_ttl'])) {
            $options['auth_key_ttl'] = self::AUTH_KEY_TTL;
        }
        $this->options = $options;
    }

    public function offsetExists($offset)
    {
        return isset($this->options[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->options[$offset]) ? $this->options[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->options[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->options[$offset]);
    }

    /**
     * Authenticate key ttl
     *
     * @return mixed
     */
    public function getAuthKeyTtl()
    {
        return $this->options['auth_key_ttl'];
    }
}