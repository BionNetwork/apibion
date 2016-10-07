<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend;

class Request
{

    /**
     * Method type
     * @var string
     */
    protected $method = null;

    /**
     * Function path
     * @var string
     */
    protected $uri = null;

    /**
     * Function path
     * @var string
     */
    protected $uploadable = array();

    /**
     * Method's parameter list
     * @var array
     */
    protected $params = array();

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
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function addUploadable(Uploadable $uploadable)
    {
        $this->uploadable[] = $uploadable;
    }

    /**
     * @return mixed
     */
    public function getUploadableList()
    {
        return $this->uploadable;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}
