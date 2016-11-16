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

class ChartController extends RestController
{

    /**
     * @ApiDoc(
     *  section="7. Графики",
     *  resource=true,
     *  description="Получение списка возможных графиков",
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
    public function getChartsAction(ParamFetcher $paramFetcher)
    {
        $representationService = $this->get('bi.chart.service');

        $params = $this->getParams($paramFetcher, 'chart');
        $filter = new \BiBundle\Entity\Filter\Chart($params);
        $representationList = $representationService->getByFilter($filter);

        $service = $this->get('api.data.transfer_object.representation_transfer_object');
        $view = $this->view($service->getObjectListData($representationList));
        return $this->handleView($view);
    }
}