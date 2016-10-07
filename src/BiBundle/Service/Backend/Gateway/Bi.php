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
    protected $gatewayUrl;

    /**
     * Gateway name
     *
     * @return mixed
     */
    public function getName()
    {
        return 'bi';
    }

    private function processUri($uri)
    {
        return $this->getGatewayUrl() . trim($uri, '/') . '?format=json';
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

        $url = $this->processUri($backendRequest->getUri());
        $client->setUri($url);

        $response = $client->send();

        $content = $response->getContent();
        if ($response->isSuccess()) {
            return json_decode($content, JSON_UNESCAPED_UNICODE);
        } else {
            //file_put_contents('/tmp/error.html', print_r($content, 1));
            return [
                'status' => 'error',
                'message' => 'Gateway error: ' . $response->getReasonPhrase(),
                'code' => $response->getStatusCode()
            ];
        }
    }
}