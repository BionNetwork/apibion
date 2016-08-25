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
    public function postReourceAction(Request $request)
    {
        $resource = new \BiBundle\Entity\Resource();

        $resourceFile = $request->files->get('resource_file');
        if (!$resourceFile) {
            //throw new HttpException(400, 'Файл не загружен');
        }

        $resourceService = $this->get('resource.service');
        $uploadResourceService = $this->get('file.upload_resource');

        $uploadedResourcePathArray = $uploadResourceService->upload($resourceFile);

        $resource->setPath($uploadedResourcePathArray['path']);
        $resourceService->save($resource);

        $view = $this->view(null, 204);
        return $this->handleView($view);
    }
}