<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend;

class Request
{
    /**
     * Data
     * @var array
     */
    protected $data = array();

    /**
     * Header
     * @var array
     */
    protected $header = array();

    /**
     * Get value by key in storage
     *
     * @param string $name key in storage
     * @return null|mixed
     */
    public function __get($name)
    {
        if(isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * Write value to storage
     *
     * @param string $name key in storage
     * @param string $value in storage
     * @return Message
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * Check if field exists in storage
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        if(isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }
}
