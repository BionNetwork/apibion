<?php

namespace ApiBundle\Controller;

use BiBundle\Entity\Purchase;
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

class ActivationController extends RestController
{

    /**
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Получение активаций по фильтру",
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
    public function getActivationsAction()
    {
        $activations = $this->getUser()->getActivation();

        $data = [];
        foreach ($activations as $activation) {
            $data[] = $activation;
        }
        $service = $this->get('api.data.transfer_object.activation_transfer_object');
        $view = $this->view($service->getListData($data));
        return $this->handleView($view);
    }



}