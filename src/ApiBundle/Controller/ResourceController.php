<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use BiBundle\Service\Upload\FilePathStrategy;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceController extends RestController
{

    /**
     *
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
     * @RequestParam(name="resource_file", description="Файл источника", nullable=false)
     * @RequestParam(name="activation_id", requirements="\d+", description="Идентификатор активации карточки", nullable=true)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postResourceAction(Request $request, ParamFetcher $params)
    {
        $params = $this->getParams($params, 'Resource');

        if($params['activation_id']) {
            $activationId = $params['activation_id'];
            $activationRepository = $this->get('repository.activation_repository');
            $activation = $activationRepository->findOneBy(['id' => $activationId, 'user' => $this->getUser()]);
            $activationPathExt = sprintf('/%d', $activation->getId());
            if(null === $activation) {
                throw new NotFoundHttpException('Активация не найдена');
            }
        } else {
            $activationPathExt = '';
        }

        $resource = new \BiBundle\Entity\Resource();

        $resourceFile = $request->files->get('resource_file');
        if (!$resourceFile) {
            throw new HttpException(400, 'Файл не загружен');
        }

        $resourceService = $this->get('bi.resource.service');

        $uploadResourceService = $this->get('file.upload_resource');
        $uploadResourceService->setUploadPath('uploads/resource' . $activationPathExt);

        $uploadedResourcePathArray = $uploadResourceService->upload($resourceFile);

        $resource->setPath($uploadedResourcePathArray['path']);
        $resource->setUser($this->getUser());
        if(!empty($activation)) {
            $resource->setActivation($activation);
        }
        $resource = $resourceService->save($resource);

        $service = $this->get('api.data.transfer_object.resource_transfer_object');
        $view = $this->view($service->getObjectData($resource), 201);
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

        $params = $this->getParams($paramFetcher, 'Resource/Filter');
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
     *  description="Получение таблиц источника",
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
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getResourceTablesAction(\BiBundle\Entity\Resource $resource, ParamFetcher $paramFetcher)
    {
        $backendService = $this->get('bi.backend.service');
        $tables = $backendService->getResourceTables($resource);
        $view = $this->view($tables);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="5. Источники",
     *  resource=true,
     *  description="Получение столбцов источника по имени таблицы",
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
    public function getResourceColumnsAction(\BiBundle\Entity\Resource $resource, ParamFetcher $paramFetcher)
    {
        $params = $this->getParams($paramFetcher, 'table_name');
        $backendService = $this->get('bi.backend.service');
        $columns = $backendService->getResourceTableColumns($resource, $params['table_name']);
        $view = $this->view($columns);
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