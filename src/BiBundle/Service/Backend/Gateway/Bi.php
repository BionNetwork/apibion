<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend\Gateway;

use BiBundle\Service\Backend;

class Bi extends AbstractGateway
{
    /**
     * Gateway url
     *
     * @var string
     */
    protected $gatewayUrl = 'http://bidemo.itgkh.ru/api/v1/';
    //protected $gatewayUrl = 'http://172.19.110.210:8000/api/v1/';

    /**
     * From whom field in Platform message
     *
     * @var string
     */
    protected $originator = 'bion';

    /**
     * Gateway name
     *
     * @return mixed
     */
    public function getName()
    {
        return 'bi';
    }

    private function processUri($path, $params) {
        return $this->getGatewayUrl() . trim($path, '/') . '/?format=json';
    }

    public function send(Backend\Request $backendRequest)
    {

        $adapter = new \Zend\Http\Client\Adapter\Curl();

        $client = new \Zend\Http\Client(
            null, array(
                'adapter' => $adapter,
                'verify' => false
            )
        );
        $client->setMethod($backendRequest->getMethod());

        /*
        $headers = $client->getRequest()->getHeaders();
        $headers->addHeaderLine('Content-type','application/json');
        $client->setHeaders($headers);
        */

        foreach ($backendRequest->getUploadableList() as $uploadable) {
            $client->setFileUpload(
                $uploadable->getFilename(),
                $uploadable->getName(),
                file_get_contents($uploadable->getPath()),
                $uploadable->getContentType()
            );
        }

        $data = $backendRequest->getData();
        
        $client->setParameterPost($data);

        $uri = $this->processUri($backendRequest->getPath(), $backendRequest->getParams());
        $client->setUri($uri);

        $response = $client->send();

        $content = $response->getContent();
        if ($response->isSuccess()) {
            return json_decode($content, JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('Ошибка связи с платформой BI');
        }

    }

    /**
     * Get response message error
     *
     * @param $body
     * @param string $default
     * @return string
     */
    protected function getError($body, $default = null)
    {

    }
}