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
        $this->gateway = new \BiBundle\Service\Backend\Gateway\Bi;
    }

    /**
     * @var
     */
    protected $container;

    /**
     * @var
     */
    protected $gateway;

    public function setServiceContainer($container)
    {
        $this->container = $container;
    }

    public function setEntityManager($em)
    {
        $this->em = $em;
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
     * Получение предпросмотра источника
     *
     * @param \BiBundle\Entity\Resource $resource
     * @param string $tableName
     *
     * @return array()
     */
    public function getResourceTablePreview(\BiBundle\Entity\Resource $resource, $tableName)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath(sprintf('datasources/%d/%s/preview', $resource->getRemoteId(), $tableName));

        $respond = $client->send($request);

        return $respond;
    }

    /**
     * Очистка кеша
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return array()
     */
    public function clearCache(\BiBundle\Entity\Activation $activation)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setPath(sprintf('cards/%d/clear_cache/', $activation->getId()));
        $respond = $client->send($request);

        return $respond;

    }

    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param \BiBundle\Entity\Resource[] $resourceList
     *
     * @return array()
     */
    public function createTree(\BiBundle\Entity\Activation $activation, array $resourceList)
    {

        // Очистка кеша перед построением нового
        $isCacheCleared = $this->clearCache($activation);

        // Построение дерева
        foreach ($resourceList as $resource) {
            $tables = $this->getResourceTables($resource);
            $data = [];
            foreach ($tables as $table) {
                $data[] = [
                    'source_id' => $resource->getRemoteId(),
                    'table_name' => $table['name']
                ];
            }
        }
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;

        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setPath(sprintf("cards/%d/create_tree/", $activation->getId()));
        $request->setData(['data' => json_encode($data)]);

        $respond = $client->send($request);

        return $respond;

    }

    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Resource $resource
     * @param \BiBundle\Entity\Resource[] $resourceList
     *
     * @return array()
     */
    public function loadData(\BiBundle\Entity\Activation $activation, array $resourceList)
    {

        // Автоматическое построение дерева по всем источникам (Для первого этапа)
        $this->createTree($activation, $resourceList);

        $data = [];
        foreach($resourceList as $resource) {
            $tables = $this->getResourceTables($resource);
            $data[$resource->getRemoteId()] = [];
            foreach ($tables as $table) {
                $columnsResponce = $this->getResourceTableColumns($resource, $table['name']);
                $columns = array_shift($columnsResponce);
                foreach ($columns as $column) {
                    $data[$resource->getRemoteId()][$table['name']][] = $column['name'];
                }
            }
        }

        /*

        $data =
            data => [
                resource_id => [
                    table_name => [
                        column_name_1,
                        column_name_2,
                        ...
                        column_name_n
                    ]
                ],
                ...
            ];


        */
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setPath(sprintf('cards/%d/load_data/', $activation->getId()));
        $request->setData(['data' => json_encode($data)]);
        $respond = $client->send($request);

        $em = $this->getEntityManager();

        $activationDoneStatus = $em->getRepository('BiBundle:ActivationStatus')->findOneBy(['code' => \BiBundle\Entity\ActivationStatus::STATUS_ACTIVE]);
        $activation->setActivationStatus($activationDoneStatus);

        $activation->setLoadDataRespond(json_encode($respond));

        $em->persist($activation);
        $em->flush();

        return $respond;
    }

    /**
     * Получение фильтров карточки
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return array()
     */
    public function getFilters(\BiBundle\Entity\Activation $activation)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setPath(sprintf('cards/%d/get_filters', $activation->getId()));

        $respond = $client->send($request);

        return $respond;
    }

    /**
     * Получение фильтров карточки
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param string $filter
     *
     * @return array()
     */
    public function getData(\BiBundle\Entity\Activation $activation, $filter)
    {
        $client = $this->container->get('bi.backend.client');
        $gateway = new \BiBundle\Service\Backend\Gateway\Bi;
        $client->setGateway($gateway);

        $request = new \BiBundle\Service\Backend\Request;
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setPath(sprintf('cards/%d/query_new', $activation->getId()));
        $request->setData(['data' => $filter]);
        $respond = $client->send($request);

        $em = $this->getEntityManager();
        $activation->setLastFilter($filter);
        $em->persist($activation);
        $em->flush();

        return $respond;
    }

}