<?php
/**
 * @package   BiBundle/Service/Platform
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */


namespace BiBundle\Service\Platform\Gateway;

use BiBundle\Service\Platform;

class Infobip extends AbstractGateway
{
    /**
     * Gateway url
     *
     * @var string
     */
    protected $gatewayUrl = 'https://api.infobip.com/Platform/1/text/single';
    /**
     * From whom field in Platform message
     *
     * @var string
     */
    protected $originator = 'Isup';

    /**
     * Gateway name
     *
     * @return mixed
     */
    public function getName()
    {
        return 'infobip';
    }

    public function send(Platform\Message $message)
    {
        // TODO: validate msg
        $adapter = new \Zend\Http\Client\Adapter\Curl();

        $client = new \Zend\Http\Client(
            null, array(
                    'adapter' => $adapter,
                    'verify' => false
                )
        );

        if (null === $this->getLogin() || null === $this->getPassword()) {
            throw new Exception("Infobip gateway error: login/password can not be empty");
        }

        // Установка данных
        $data = array(
            'from' => $this->getOriginator(),
            'to' => $message->getPhone(),
            'text' => $message->getText()
        );

        $request = new \Zend\Http\Request();
        $request->setUri($this->getGatewayUrl());
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $headers = new \Zend\Http\Headers();
        $headers->addHeaders([
            'Content-Type' => "application/json",
            'Authorization' => sprintf("Basic %s", base64_encode(sprintf("%s:%s", $this->getLogin(), $this->getPassword())))
        ]);
        $request->setHeaders($headers);
        $request->setContent(json_encode($data));

        $response = $client->send($request);
        $body = json_decode($response->getBody(), true);

        if ($response->isSuccess()) {

            if (!empty($body['messages']) && array_key_exists('status', $body['messages'][0])) {
                return $body['messages'][0]['messageId'];
            } else {
                $error = var_export($message, true);
                $errorResponse = $this->getError($body);
                if (null !== $errorResponse) {
                    $error = $errorResponse;
                }
                throw new Exception('Ошибка при отправке Platform: ' . $error);
            }
        } else {
            $error = $this->getError($body, 'Ошибка при отправке запроса');
            throw new Exception($error);
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
        if (!empty($body['requestError']['serviceException'])) {
            $error = sprintf("%s: %s",
                $body['requestError']['serviceException']['messageId'],
                $body['requestError']['serviceException']['text']
            );
        } else {
            $error = $default;
        }
        return $error;
    }
}