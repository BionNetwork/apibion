<?php
/**
 * @package   BiBundle/Service/Platform
 */

namespace BiBundle\Service\Platform;
/**
 * Platform message
 *
 * Platform object is a container with any number of fields
 * Fields can be accessed through object notation or array access
 */
class Message implements \ArrayAccess
{
    /**
     * Message storage
     *
     * @var array
     */
    protected $data = array();
    /**
     * Receiver phone
     *
     * @var string
     */
    protected $phone;
    /**
     * Text to send
     *
     * @var string
     */
    protected $text;

    /**
     * @param string $phone
     * @return Message
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }
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
