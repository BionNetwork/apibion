<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceController extends RestController
{
    /**
     * Получение информации по источнику (список таблиц и список колонок к каждой таблице)
     * ### Response ###
     *     "data": {
     *          "TDSheet": {
     *              "columns": [
     *              {
     *                  "max_length": null,
     *                  "name": "Дата",
     *                  "origin_type": "object",
     *                  "type": "text"
     *              },
     *              ...
     *          ]}
     *      }
     *
     * @ApiDoc(
     *  section="5. Источники",
     *  resource=true,
     *  description="Информация по источнику",
     *  statusCodes={
     *          201="Создано",
     *          400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @param \BiBundle\Entity\Resource $resource
     * @return Response
     */
    public function getResourceAction(\BiBundle\Entity\Resource $resource)
    {
        $backendService = $this->get('bi.backend.service');

        $data = $backendService->getResourceStructure($resource);

        $view = $this->view($data);
        return $this->handleView($view);
    }
    /**
     * ### Connection types
     *
     * Postgresql
     * Mysql
     * MsSql
     * Oracle
     * Excel
     * Csv
     * Text
     *
     * @ApiDoc(
     *  section="5. Источники",
     *  resource=true,
     *  description="Добавление источника",
     *  statusCodes={
     *          201="Создано",
     *          400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @RequestParam(name="resource_file", description="Файл источника", nullable=true)
     * @RequestParam(name="activation_id", requirements="\d+", description="Идентификатор активации карточки", nullable=true)
     * @RequestParam(name="connection_type", requirements="\w+", description="Тип соединения с источником", nullable=false)
     * @RequestParam(name="connection_db", requirements="\w+", description="База данных для соединения с источником", nullable=true)
     * @RequestParam(name="connection_host", description="Хост для соединения с базой источником", nullable=true)
     * @RequestParam(name="connection_login", description="Логин для соединения с базой источника", nullable=true)
     * @RequestParam(name="connection_pass", description="Пароль для соединения с базой источника", nullable=true)
     * @RequestParam(name="connection_port", description="Порт для соединения с базой источника", nullable=true)
     *
     * @param Request $request
     *
     * @param ParamFetcher $params
     * @return Response
     */
    public function postResourceAction(Request $request, ParamFetcher $params)
    {
        $params = $this->getParams($params, 'resource');

        $service = $this->get('api.request.resource');
        try {
            $resource = $service->getResource($params);
        } catch (\Exception $e) {
            throw new HttpException(400, $e->getMessage());
        }

        if($params['activation_id']) {
            $activationId = $params['activation_id'];
            $activationRepository = $this->get('repository.activation_repository');
            /** @var \BiBundle\Entity\Activation $activation */
            $activation = $activationRepository->findOneBy(['id' => $activationId, 'user' => $this->getUser()]);
            if(null === $activation) {
                throw new NotFoundHttpException('Активация не найдена');
            }
            $resource->setActivation($activation);
        }

        $resourceService = $this->get('bi.resource.service');

        $resource->setUser($this->getUser());

        $resource = $resourceService->save($resource);

        $dto = $this->get('api.data.transfer_object.resource_transfer_object');
        $view = $this->view($dto->getObjectData($resource), 201);
        return $this->handleView($view);
    }


    /**
     * @ApiDoc(
     *  section="5. Источники",
     *  resource=true,
     *  description="Получение перечня источников данных по фильтру",
     *  statusCodes={
     *         200="При успешном получении данных",
     *         400="Ошибка получения данных"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *

     *
     * @QueryParam(name="id", requirements="\d+", description="Идентификатор источника", nullable=true)
     * @QueryParam(name="activation_id", requirements="\d+", description="Идентификатор активации источника", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getResourcesAction(ParamFetcher $paramFetcher)
    {
        $resourceService = $this->get('bi.resource.service');

        $params = $this->getParams($paramFetcher, 'resource');
        $filter = new \BiBundle\Entity\Filter\Resource($params);
        $filter->user_id = $this->getUser()->getId();
        $resourceList = $resourceService->getByFilter($filter);

        $service = $this->get('api.data.transfer_object.resource_transfer_object');
        $view = $this->view($service->getObjectListData($resourceList));
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="5. Источники",
     *  resource=true,
     *  description="Получение предпросмотра источника по имени таблицы",
     *  statusCodes={
     *         200="При успешном получении данных",
     *         400="Ошибка получения данных"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @QueryParam(name="table_name", allowBlank=false, description="Наименование таблицы")
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getResourcePreviewAction(\BiBundle\Entity\Resource $resource, ParamFetcher $paramFetcher)
    {
        $params = $this->getParams($paramFetcher, 'table_name');
        $backendService = $this->get('bi.backend.service');
        $preview = $backendService->getResourceTablePreview($resource, $params['table_name']);
        $view = $this->view($preview);
        return $this->handleView($view);
    }

}