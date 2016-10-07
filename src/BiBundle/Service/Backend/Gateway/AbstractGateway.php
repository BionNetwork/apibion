<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend\Gateway;

/**
 * Abstract Gateway class
 */

abstract class AbstractGateway implements GatewayInterface
{
    protected $config;
    /**
     * Login in service
     *
     * @var string
     */
    protected $login;
    /**
     * Password in service
     *
     * @var string
     */
    protected $password;
    /**
     * Gateway url
     *
     * @var string
     */
    protected $gatewayUrl;

    /**
     * @param string $gatewayUrl
     */
    public function setGatewayUrl($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    /**
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->gatewayUrl;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        if(!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach($options as $key => $value) {
            if(in_array($key, array('login', 'password', 'gatewayUrl'))) {
                $this->{$key} = $value;
            }
        }
    }


    /**
     * @throws \RuntimeException
     * @return mixed
     */
    public function getConfig()
    {
        if (null === $this->config) {
            throw new \RuntimeException($this->getName() . " config is not set");
        }
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}
