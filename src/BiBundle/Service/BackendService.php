<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use BiBundle\Service\Backend\Exception;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Backend service
 */
class BackendService extends UserAwareService
{


    public function __construct()
    {

    }

    /**
     * @var
     */
    protected $container;

    public function setServiceContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Отправка источника данных на сервер BI
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return int
     */
    public function putResource(\BiBundle\Entity\Resource $resource)
    {

        // todo Сделать создание источников не только для XLS файлов

        $client = $this->container->get('bi.backend.client');

        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setPath('/datasources');

        $uploadDir = $this->container->getParameter('upload_dir');
        $uploadFilePath = implode(DIRECTORY_SEPARATOR, [$uploadDir, $resource->getPath()]);

        $uploadable = new Backend\Uploadable;
        $uploadable->setFilename(basename($uploadFilePath));
        $uploadable->setName('file');
        $uploadable->setPath($uploadFilePath);
        $uploadable->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $request->addUploadable($uploadable);

        $body = [
            'csrfmiddlewaretoken' => '3eZ2QbotuZ9OkSt3J8jBYMBNZVyQHwRY',
            'db' => null,
            'host' => null,
            'port' => null,
            'login' => null,
            'password' => null,
            'conn_type' => 5,
            'user_id' => null,
            'settings' => [],

        ];
        $request->setData($body);

        $respond = $client->send($request);

        if($respond['id']) {
            $resource->setRemoteId($respond['id']);
        } else {
            throw new Exception('Не удалось обработать файл');
        }
        return $resource;
    }

    /**
     * Получение источника с сервера BI
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return int
     */
    public function getAllResources()
    {
        $client = $this->container->get('bi.backend.client');

        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath('/datasources');
        
        $respond = $client->send($request);

        return $respond;

    }

    /**
     * Получение источника с сервера BI
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return int
     */
    public function getResource(\BiBundle\Entity\Resource $resource)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath(sprintf('datasources/%d', $resource->getRemoteId()));

        $respond = $client->send($request);

        return $respond;


    }

    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return array()
     */
    public function getResourceTables(\BiBundle\Entity\Resource $resource)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath(sprintf('datasources/%d/tables', $resource->getRemoteId()));
        $request->setParams([$resource->getRemoteId()]);

        $respond = $client->send($request);

        return $respond;
    }

    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Resource $resource
     * @param string $tableName
     *
     * @return array()
     */
    public function getResourceTableColumns(\BiBundle\Entity\Resource $resource, $tableName)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath(sprintf('datasources/%d/%s', $resource->getRemoteId(), $tableName));

        $respond = $client->send($request);

        return $respond;
    }


    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Resource $resource
     * @param string $tableName
     *
     * @return array()
     */
    public function createTree(\BiBundle\Entity\Activation $activation, $data)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath(sprintf('cards/$d/create_tree/', $activation->getId()));
        $request->setData($data);

        $respond = $client->send($request);

        return $respond;
    }

}