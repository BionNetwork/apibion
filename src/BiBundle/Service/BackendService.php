<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationStatus;
use BiBundle\Service\Backend\Client;
use BiBundle\Service\Backend\Exception;
use BiBundle\Service\Backend\Gateway\UrlOptions;
use BiBundle\Service\Backend\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Backend service
 */
class BackendService extends UserAwareService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ContainerInterface
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
     * @return int
     * @throws Backend\Client\Exception
     * @throws Exception
     */
    public function putResource(\BiBundle\Entity\Resource $resource)
    {
        // todo Сделать создание источников не только для XLS файлов
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setUri(UrlOptions::DATA_SOURCES_URL);
        $settings = $resource->getSettings();

        $body = [
            'db' => null,
            'host' => null,
            'port' => null,
            'login' => null,
            'password' => null,
            'conn_type' => ucfirst($settings['type']),
            'user_id' => null,
            'settings' => [],
        ];

        if (isset($settings['file'])) {
            $uploadDir = $this->getContainer()->getParameter('upload_dir');
            $uploadFilePath = implode(DIRECTORY_SEPARATOR, [$uploadDir, $settings['file']['path']]);

            $uploadable = new Backend\Uploadable;
            $uploadable->setFilename(basename($uploadFilePath));
            $uploadable->setName('file');
            $uploadable->setPath($uploadFilePath);
            $uploadable->setContentType($settings['file']['mimeType']);

            $request->addUploadable($uploadable);
        } elseif(isset($settings['connection'])) {
            $connection = $settings['connection'];
            foreach (['db', 'host', 'port', 'login', 'password'] as $key) {
                $body[$key] = $connection[$key];
            }
        }

        $request->setData($body);

        $respond = $client->send($request);

        if(isset($respond['id'])) {
            $resource->setRemoteId($respond['id']);
        } else {
            throw new HttpException(400, 'Ошибка создания источника: '.$respond['message']);
        }
        return $resource;
    }

    /**
     * Получение источника с сервера BI
     *
     * @return int
     */
    public function getAllResources()
    {
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setUri(UrlOptions::DATA_SOURCES_URL);

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
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setUri(sprintf(UrlOptions::DATA_SOURCES_ITEM_URL, $resource->getRemoteId()));

        $respond = $client->send($request);

        return $respond;
    }

    /**
     * Resource structure
     *
     * @param \BiBundle\Entity\Resource $resource
     * @return array
     */
    public function getResourceStructure(\BiBundle\Entity\Resource $resource)
    {
        $data = [];
        $tables = $this->getResourceTables($resource);
        foreach ($tables as $table) {
            $columns = $this->getResourceTableColumns($resource, $table);
            $data[$table] = [
                'columns' => $columns[$table]
            ];
        }

        return $data;
    }

    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Resource $resource
     *
     * @return array()
     */
    protected function getResourceTables(\BiBundle\Entity\Resource $resource)
    {
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setUri(sprintf(UrlOptions::DATA_SOURCES_TABLES_URL, $resource->getRemoteId()));
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
    protected function getResourceTableColumns(\BiBundle\Entity\Resource $resource, $tableName)
    {
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setUri(sprintf(UrlOptions::DATA_SOURCES_TABLE_INFO_URL, $resource->getRemoteId(), $tableName));

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
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setUri(sprintf(UrlOptions::DATA_SOURCES_TABLE_PREVIEW_URL, $resource->getRemoteId(), $tableName));

        $respond = $client->send($request);

        foreach($respond as $index => $row) {
            $respond[$index]['id'] = $index+1;
        }

        return $respond;
    }

    /**
     * Создание дерева по источнику
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param \BiBundle\Entity\Resource[] $resourceList
     *
     * @return array()
     */
    public function createTree(\BiBundle\Entity\Activation $activation, array $resourceList)
    {
        // Построение дерева
        $data = [];
        foreach ($resourceList as $resource) {
            $tables = $this->getResourceTables($resource);
            foreach ($tables as $table) {
                $data[] = [
                    'source_id' => $resource->getRemoteId(),
                    'table_name' => $table
                ];
            }
        }
        $client = $this->getClient();

        $request = new Request();

        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setUri(sprintf(UrlOptions::CARDS_TREE_CREATE_URL, $activation->getId()));
        $request->setData(['data' => json_encode($data)]);

        $respond = $client->send($request);

        return $respond;

    }

    /**
     * Получение таблиц источника
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param \BiBundle\Entity\Resource[] $resourceList
     * @return array
     * @throws Backend\Client\Exception
     */
    public function loadData(\BiBundle\Entity\Activation $activation, array $resourceList)
    {
        // Если данные уже грузились и если данные о хешах верные, то возвращаем эти данные
        if(\BiBundle\Entity\ActivationStatus::STATUS_ACTIVE === $activation->getActivationStatus()->getCode()) {
            $loadDataRespond = json_decode($activation->getLoadDataRespond(), JSON_UNESCAPED_UNICODE);
            if(is_array($loadDataRespond) && array_key_exists('sources', $loadDataRespond)) {
                return $loadDataRespond;
            }
        }

        // Автоматическое построение дерева по всем источникам (Для первого этапа)
        $this->createTree($activation, $resourceList);

        $data = [];
        foreach($resourceList as $resource) {
            $tables = $this->getResourceTables($resource);
            $data[$resource->getRemoteId()] = [];
            foreach ($tables as $table) {
                $columnsResponse = $this->getResourceTableColumns($resource, $table);
                $columns = array_shift($columnsResponse);
                foreach ($columns as $column) {
                    $data[$resource->getRemoteId()][$table][] = $column['name'];
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
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setUri(sprintf(UrlOptions::CARDS_LOAD_DATA_URL, $activation->getId()));
        $request->setData(['data' => json_encode($data)]);
        $respond = $client->send($request);

        $em = $this->getEntityManager();

        $activationDoneStatus = $em->getRepository('BiBundle:ActivationStatus')
            ->findOneBy(['code' => \BiBundle\Entity\ActivationStatus::STATUS_ACTIVE]);
        $activation->setActivationStatus($activationDoneStatus);

        $activation->setLoadDataRespond(json_encode($respond, JSON_UNESCAPED_UNICODE));

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
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_GET);
        $request->setUri(sprintf(UrlOptions::CARDS_FILTERS_URL, $activation->getId()));

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
        $filter = $filter ?: $activation->getLastFilter();
        $client = $this->getClient();

        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setUri(sprintf(UrlOptions::CARDS_QUERY_URL, $activation->getId()));
        $request->setData(['data' => $filter]);
        $respond = $client->send($request);

        $em = $this->getEntityManager();
        $activation->setLastFilter($filter);
        $em->persist($activation);
        $em->flush();

        return $respond;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $client = $this->container->get('bi.backend.client');
            $this->client = $client;
        }
        return $this->client;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function makeQuery(Activation $activation, $query)
    {
        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setUri(sprintf(UrlOptions::CARDS_QUERY_URL, $activation->getId()));
        $request->setData(['query' => $query]);
        $result = $this->getClient()->send($request);

        return $result;
    }

    public function createActivation(Activation $activation)
    {
        if(!$activation->getId()) {
            throw new \ErrorException('Activation has no id');
        }
        $request = new Request();
        $request->setMethod(\Zend\Http\Request::METHOD_POST);
        $request->setUri(UrlOptions::CUBE_CREATE_URL);
        $request->setData(['key' => $activation->getId()]);
        if(!$this->getClient()->post($request)->isSuccess()) {
            throw new Exception('Unable to create activation');
        }
    }
}