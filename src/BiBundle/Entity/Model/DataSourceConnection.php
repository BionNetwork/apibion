<?php

/**
 * @package    BiBundle\Entity\Model
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
namespace BiBundle\Entity\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DataSourceConnection
{
    /**
     * Type of connection
     *
     * @Assert\NotBlank();
     * @var string
     */
    private $type;
    /**
     * Database name
     *
     * @Assert\NotBlank()
     * @var string
     */
    private $db;
    /**
     * Database host
     *
     * @Assert\NotBlank()
     * @var string
     */
    private $host;
    /**
     * Database port
     *
     * @Assert\NotBlank()
     * @var int
     */
    private $port;
    /**
     * Login to connect
     *
     * @Assert\NotBlank()
     * @var string
     */
    private $login;
    /**
     * Password to connect
     *
     * @Assert\NotBlank()
     * @var string
     */
    private $password;

    public static function fromUserInput(array $data)
    {
        $self = new self();
        $mapping = self::getInputMappings();
        foreach ($data as $key => $value) {
            if (isset($mapping[$key])) {
                $param = $mapping[$key];
                $self->{$param} = $value;
            }
        }

        return $self;
    }

    /**
     * Mapping to current object fields
     *
     * @return array
     */
    protected static function getInputMappings()
    {
        $data = [
            'connection_db' => 'db',
            'connection_type' => 'type',
            'connection_host' => 'host',
            'connection_port' => 'port',
            'connection_login' => 'login',
            'connection_pass' => 'password'
        ];
        return $data;
    }

    public static function getReversedInputMapping($value)
    {
        $data = array_flip(self::getInputMappings());
        return isset($data[$value]) ? $data[$value] : 'unknown field';
    }

    /**
     * Converts to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'db' => $this->db,
            'type' => $this->type,
            'host' => $this->host,
            'port' => $this->port,
            'login' => $this->login,
            'password' => $this->password
        ];
    }
}