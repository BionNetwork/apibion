<?php
/**
 * @package   BiBundle/Service/Platform
 * @author    miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
/**
 * @namespace
 */
namespace BiBundle\Service\Platform\Gateway;

/**
 * Abstract Gateway class
 */

abstract class AbstractGateway implements IGateway
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
     * From whom field in Platform message
     *
     * @var string
     */
    protected $originator;

    /**
     * @param string $originator
     */
    public function setOriginator($originator)
    {
        if (mb_strlen($originator) > 11) {
            $originator = mb_substr($originator, 0, 11);
        }
        $this->originator = $originator;
    }

    /**
     * @return string
     */
    public function getOriginator()
    {
        if(mb_strlen($this->originator) > 11) {
            $this->originator = mb_substr($this->originator, 0, 11);
        }
        return $this->originator;
    }

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
            if(in_array($key, array('login', 'password', 'gatewayUrl', 'originator'))) {
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
    /**
     * Set data from external source
     *
     * @param array $data from external source
     * @throws \RuntimeException
     * @return mixed
     */
    public function setFromArray(array $data)
    {
        $config = $this->getConfig();
        if (!empty($data['originator'])) {
            $this->setOriginator($data['originator']);
        }

        if (!empty($config)) {
            foreach (array('login', 'password') as $key) {
                if (!isset($config[$key])) {
                    throw new \RuntimeException(
                        sprintf(
                            "Channel %s does not have %s parameter",
                            $this->getName(),
                            $key
                        ));
                }
            }
            if (!empty($config['gateway_url'])) {
                $this->setGatewayUrl($config['gateway_url']);
            }

            $this->setLogin($config['login']);
            $this->setPassword($config['password']);
        } else {
            throw new \RuntimeException(sprintf("%s channel is not set", $this->getName()));
        }
    }
}
