<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BiBundle\Entity\Dashboard as EntityDashboard;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use BiBundle\Service\Upload\FilePathStrategy;

class DashboardController extends RestController
{

    /**
     * @ApiDoc(
     *  section="6. Рабочие столы",
     *  resource=true,
     *  description="Получение рабочих столов пользователя по фильтру",
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
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор рабочего стола")
     * @QueryParam(name="limit", default="20", requirements="\d+", description="Количество запрашиваемых рабочих столов" )
     * @QueryParam(name="offset", nullable=true, requirements="\d+", description="Смещение, с которого нужно начать просмотр" )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getDashboardsAction(ParamFetcher $paramFetcher)
    {
        $dashboardService = $this->get('bi.dashboard.service');

        $params = $this->getParams($paramFetcher, 'dashboard');
        $params['user_id'] = $this->getUser()->getId();
        $filter = new \BiBundle\Entity\Filter\Dashboard($params);

        $dashboards = $dashboardService->getByFilter($filter);

        $service = $this->get('api.data_transfer_object.dashboard_transfer_object');
        $view = $this->view($service->getListData($dashboards));

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
     *                  "dashboard":{
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
     *              "id":<new dashboard id>
     *          },
     *          "time":<time request>
     *      }
     *
     * @ApiDoc(
     *  section="6. Рабочие столы",
     *  resource=true,
     *  description="Создание рабочего стола",
     *  statusCodes={
     *         200="При успешном рабочего стола",
     *         400="Ошибка создания рабочего стола"
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
     * @RequestParam(name="name", allowBlank=false, description="Name of dashboard")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDashboardAction(Request $request)
    {
        $dashboardService = $this->get('bi.dashboard.service');
        $dashboard = new EntityDashboard();
        $form = $this->createForm(\BiBundle\Form\DashboardType::class, $dashboard);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }
        $dashboardService->save($dashboard);
        $data = [
            'id' => $dashboard->getId()
        ];
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     *
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Bad Request"
     *              },
     *              "errors": null
     *      }
     *
     * ### Success Response ###
     *      {
     *          "data":{
     *              "id":<dashboard id>
     *          },
     *          "time":<time request>
     *      }
     *
     * @ApiDoc(
     *  section="6. Рабочие столы",
     *  resource=true,
     *  description="Получение рабочего стола",
     *  statusCodes={
     *         200="При успешном запросе",
     *         400="Ошибка запроса"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    },
     *  output="\ApiBundle\Service\DataTransferObject\Object\DashboardValueObject"
     * )
     *
     * @param EntityDashboard $dashboard
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDashboardAction(EntityDashboard $dashboard)
    {
        $service = $this->get('api.data_transfer_object.dashboard_transfer_object');
        $view = $this->view($service->getObjectData($dashboard));
        return $this->handleView($view);
    }

    /**
     * ### Minimal Response ###
     *
     *      {
     *      }
     *
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Validation Failed"
     *              },
     *              "errors": {
     *                  "user":{
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
     *      }
     *
     * @ApiDoc(
     *  section="6. Рабочие столы",
     *  resource=true,
     *  description="Редактирование рабочего стола",
     *  statusCodes={
     *         204="При успешном обновлении",
     *         400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access token header",
     *          "required"="true"
     *      }
     *    },
     *  input={
     *      "class"="BiBundle\Form\Type\Dashboard",
     *      "name"=""
     *  }
     * )
     *
     *
     * @param \BiBundle\Entity\Dashboard $dashboard
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putDashboardAction(\BiBundle\Entity\Dashboard $dashboard, Request $request)
    {
        $form = $this->createForm('BiBundle\Form\Type\Dashboard', $dashboard);

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }

        $dashboardService = $this->get('bi.dashboard.service');
        $dashboardService->save($dashboard);
        $view = $this->view(null, 204);
        return $this->handleView($view);
    }


    /**
     * @ApiDoc(
     *  section="6. Рабочие столы",
     *  resource=true,
     *  description="Получение активаций рабочего стола по фильтру",
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
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор рабочего стола")
     * @QueryParam(name="limit", default="20", requirements="\d+", description="Количество запрашиваемых проектов" )
     * @QueryParam(name="offset", nullable=true, requirements="\d+", description="Смещение, с которого нужно начать просмотр" )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getDashboardActivationsAction(\BiBundle\Entity\Dashboard $dashboard, ParamFetcher $paramFetcher)
    {
        $activationsService = $this->get('bi.activation.service');
        $params = $this->getParams($paramFetcher, 'Filter\Activation');
        $filter = new \BiBundle\Entity\Filter\Activation($params);
        $filter->dashboard_id = $dashboard->getId();
        $cards = $activationsService->getByFilter($filter);
        $service = $this->get('api.data.transfer_object.activation_transfer_object');
        $view = $this->view($service->getListData($cards));
        return $this->handleView($view);
    }

}