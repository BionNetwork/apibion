<?php

namespace BiBundle\Service;

use BiBundle\Service\Exception\UserCard\AlreadyPurchasedException;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Card;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CardService extends UserAwareService
{

    /**
     * Возвращает проект по фильтру
     *
     * @param \BiBundle\Entity\Filter\Card $filter
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Card $filter)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('BiBundle:Card')->findByFilter($filter);

        // Развернем в структуру
        $resultArray = [];
        foreach ($items as $row) {
            $resultArray[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'type' => $row['type'],

            ];
        }
        return $resultArray;
    }

    /**
     * Тестовый вызов удаленного API
     */
    public function call()
    {
        $adapter = new \Zend\Http\Client\Adapter\Curl();
        $client = new \Zend\Http\Client(
            null,
            [
                'adapter' => $adapter,
                'verify' => false
            ]
        );

        // Установка данных
        $data = array(
            /*'from' => $this->getOriginator(),
            'to' => ,
            'text' => $message->getText()*/
        );

        $request = new \Zend\Http\Request();
        $request->setUri('http://bidemo.itgkh.ru/api/v1/datasources/');
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        /*$headers = new \Zend\Http\Headers();
        $headers->addHeaders([
            'Content-Type' => "application/json",
            'Authorization' => sprintf("Basic %s", base64_encode(sprintf("%s:%s", $this->getLogin(), $this->getPassword())))
        ]);
        $request->setHeaders($headers);*/
        $request->setContent(json_encode($data));

        $response = $client->send($request);
        $body = json_decode($response->getBody(), true);
        dump($body);
        /*if ($response->isSuccess()) {

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
        }*/

    }
}
