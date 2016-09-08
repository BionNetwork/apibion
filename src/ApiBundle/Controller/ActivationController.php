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
     * @param \BiBundle\Entity\Activation $activation
     * @param \BiBundle\Entity\Dashboard $dashboard
     *
     * @Route("/activation/{activation}/dashboard/{dashboard}", requirements={"activation": "\d+", "dashboard": "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postActivationDashboardAction(\BiBundle\Entity\Activation $activation, \BiBundle\Entity\Dashboard $dashboard)
    {
        $dashboardActivationService = $this->get('bi.dashboard_activation.service');
        $dashboardActivation = new \BiBundle\Entity\DashboardActivation();

        $dashboardActivation->setActivation($activation);
        $dashboardActivation->setDashboard($dashboard);

        $dashboardActivationService->save($dashboardActivation);

        $data = [];
        $view = $this->view($data, 200);
        return $this->handleView($view);
    }

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
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Построение дерева связей (Этап активации №1)",
     *  statusCodes={
     *          200="Успех",
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
     * @Route("/activation/{activation}/createtree", requirements={"activation": "\d+"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postActivationTreeAction(\BiBundle\Entity\Activation $activation)
    {
        $resourceList = $activation->getResource();
        $resourceListArray = [];
        foreach ($resourceList as $resource) {
            $resourceListArray[] = $resource;
        }

        $backendService = $this->get('bi.backend.service');
        $result = $backendService->createTree($activation, $resourceListArray);

        $view = $this->view($result);
        return $this->handleView($view);
    }


    /**
     *
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Загрузка данных их источников (Этап активации №2)",
     *  statusCodes={
     *          200="Успех",
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
     * @Route("/activation/{activation}/loaddata", requirements={"activation": "\d+"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postActivationLoaddataAction(\BiBundle\Entity\Activation $activation)
    {
        $resourceList = $activation->getResource();
        $resourceListArray = [];
        foreach ($resourceList as $resource) {
            $resourceListArray[] = $resource;
        }

        // todo Replace caching filters to database by caching to Redis
        $backendService = $this->get('bi.backend.service');
        $result = $backendService->loadData($activation, $resourceListArray);

        $view = $this->view($result);
        return $this->handleView($view);
    }


    /**
     *
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Получение фильтров конкретной активации (Этап активации №2.5)",
     *  statusCodes={
     *          200="Успех",
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
     * @Route("/activation/{activation}/getfilters", requirements={"activation": "\d+"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getFiltersAction(\BiBundle\Entity\Activation $activation)
    {
        $resourceList = $activation->getResource();
        $backendService = $this->get('bi.backend.service');

        $resourceListArray = [];
        foreach ($resourceList as $resource) {
            $resourceListArray[] = $resource;
        }

        $result = $backendService->getFilters($activation, $resourceListArray);

        $view = $this->view($result);
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Получение данных конкретной активации и сохранение фильтров инициализирующего запроса (Этап активации №3)",
     *  statusCodes={
     *          200="Успех",
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
     * @Route("/activation/{activation}/getdata", requirements={"activation": "\d+"})
     *
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор карточки")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */
    public function getDataAction(\BiBundle\Entity\Activation $activation, ParamFetcher $paramFetcher)
    {
        $params = $this->getParams($paramFetcher, 'card');
        $filter = new \BiBundle\Entity\Filter\Card($params);

        $backendService = $this->get('bi.backend.service');

        $result = $backendService->getData($activation, $filter);

        $view = $this->view($result);
        return $this->handleView($view);
    }

}