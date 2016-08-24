<?php
/**
 * @package   BiBundle/Service/Platform
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

/**
 * @namespace
 */
namespace BiBundle\Service\Platform\Gateway;

use BiBundle\Service\Platform;

/**
 * Send Platform messages through Platformtraffic.ru
 */
class Platformtraffic extends AbstractGateway
{
    /**
     * Gateway url
     *
     * @var string
     */
    protected $gatewayUrl = 'https://api.Platformtraffic.ru/multi.php';
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
        return 'Platformtraffic';
    }


    /**
     * Send message
     *
     * @param Platform\Message $message
     * @throws Exception
     * @return bool|int|mixed
     */
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
            throw new Exception("Platformtraffic gateway error: login/password can not be empty");
        }

        // Установка POST данных
        // Полное API: https://www.Platformtraffic.ru/doc/techdoc_corp.doc
        $rus = 5; // message on russian in utf-8 encoding
        if (null !== $message->translit) {
            $translit = (bool)$message->translit;
            if ($translit) {
                $rus = 0;
            }
        }

        $data = array(
            'login' => $this->getLogin(),
            'password' => $this->getPassword(),
            'originator' => $this->getOriginator(),
            'phones' => $message->getPhone(),
            'message' => 5 == $rus ? $message->getText() : mb_convert_encoding(
                $message->getText(),
                'cp1251',
                'utf-8'
            ),
            'rus' => $rus,
            'autotruncate' => 1,
            'max_parts' => 7,
            'want_Platform_ids' => 1,
            'gap' => 0.05
        );


        $client->setParameterPost($data);
        $client->setUri($this->getGatewayUrl());
        $client->setMethod(\Zend\Http\Request::METHOD_POST);

        $response = $client->send();
        /**
         * <reply>
            <result>OK</result>
            <code></code>
            <description>queued 2 messages</description>
            <message_infos>
                <message_info>
                <phone>79051112233</phone>
                <Platform_id>8287366071</Platform_id>
                <push_id>a</push_id>
                </message_info>
                <message_info>
                <phone>79051112233</phone>
                <Platform_id>8287366073</Platform_id>
                <push_id>a</push_id>
                </message_info>
            </message_infos>
           </reply>
         */
        $body = $response->getBody();

        if ($response->isSuccess()) {
            $xml = simplexml_load_string($body);

            if (!empty($xml->message_infos[0]->message_info->Platform_id)) {
                return $xml->message_infos[0]->message_info->Platform_id->__toString();
            } else {
                $error = $xml->description->__toString();
                throw new Exception('Ошибка при отправке Platform: ' . $error);
            }
        } else {
            throw new Exception('Ошибка при отправке запроса');
        }
    }
}
