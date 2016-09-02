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
     *          204="Успех",
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
     * @RequestParam(name="activation_id", description="Идентификатор активации карточки", nullable=true)
     * @Route("/resource/add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postResourceAction(Request $request)
    {
        $resource = new \BiBundle\Entity\Resource();

        $resourceFile = $request->files->get('resource_file');
        file_put_contents('/tmp/debug', print_r($resourceFile, 1));
        if (!$resourceFile) {
            throw new HttpException(400, 'Файл не загружен');
        }

        $resourceService = $this->get('bi.resource.service');

        $uploadResourceService = $this->get('file.upload_resource');
        $uploadResourceService->setUploadPath('uploads/resource');

        $uploadedResourcePathArray = $uploadResourceService->upload($resourceFile);

        $resource->setPath($uploadedResourcePathArray['path']);
        $resource->setUser($this->getUser());
        $resource = $resourceService->save($resource);

        $view = $this->view(null, 204);
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

}