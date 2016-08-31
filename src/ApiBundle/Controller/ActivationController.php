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

    /**
     *
     * ### Failed Response ###
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Validation Failed"
     *              },
     *              "errors": {
     *                  "dashboardCard":{
     *                      "errors":[
     *                          <errorMessage 1>,
     *                          <...>,
     *                          <errorMessage N>
     *                      ],
     *                      "children": {
     *                           <field_name>: {
     *                              "errors": [
     *                                  <errorMessage 1>,
     *                                  <...>,
     *                                  <errorMessage N>
     *                              ],
     *                              "children": null
     *                          }
     *                      }
     *                  }
     *              }
     *          }
     *
     * ### Success Response ###
     *      {
     *          "data":{
     *              "id":<new dashboard card id>
     *          },
     *          "time":<time request>
     *      }
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Привязка активации к рабочему столу",
     *  statusCodes={
     *         200="При успешном создании экземпляра карточки",
     *         400="Ошибка создания экземпляра карточки"
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
     * @RequestParam(name="name", allowBlank=false, description="Name of dashboard card")
     *
     * @param \BiBundle\Entity\Dashboard $dashboard
     * @param \BiBundle\Entity\Activation $activation
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDashboardsActivationAction(\BiBundle\Entity\Dashboard $dashboard, \BiBundle\Entity\Activation $activation, Request $request)
    {
        $dashboardActivationService = $this->get('bi.dashboard_activation.service');
        $dashboardActivation = new DashboardActivation();

        $dashboardActivation->setActivation($activation);
        $dashboardActivation->setDashboard($dashboard);

        $form = $this->createForm(\BiBundle\Form\DashboardActivationType::class, $dashboardActivation);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }
        $dashboardActivationService->save($dashboardActivation);
        $data = [
            'id' => $dashboardActivation->getId()
        ];
        $view = $this->view($data);
        return $this->handleView($view);
    }

}