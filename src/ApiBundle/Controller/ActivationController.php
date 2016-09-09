<?php

namespace ApiBundle\Controller;

use BiBundle\Entity\ActivationStatus;
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
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор активации")
     * @QueryParam(name="card_id", allowBlank=true, requirements="\d+", description="Идентификатор карточки")
     * @QueryParam(name="activation_status", allowBlank=true, requirements=".+", description="Код статуса (pending/active/deleted)")
     * @QueryParam(name="limit", default="20", requirements="\d+", description="Количество запрашиваемых проектов" )
     * @QueryParam(name="offset", nullable=true, requirements="\d+", description="Смещение, с которого нужно начать просмотр" )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getActivationsAction(ParamFetcher $paramFetcher)
    {

        $activationService = $this->get('bi.activation.service');
        $params = $this->getParams($paramFetcher, 'activation');
        $params['user_id'] = $this->getUser()->getId();
        $filter = new \BiBundle\Entity\Filter\Activation($params);
        $activations = $activationService->getByFilter($filter);

        $service = $this->get('api.data.transfer_object.activation_transfer_object');
        $view = $this->view($service->getListData($activations));
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
     * @QueryParam(name="json", allowBlank=true, requirements=".+", description="Сериализованный в JSON фильтр")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */
    public function getDataAction(\BiBundle\Entity\Activation $activation, ParamFetcher $paramFetcher)
    {
        $activationStatusCode = $activation->getActivationStatus()->getCode();
        if(ActivationStatus::STATUS_PENDING === $activationStatusCode) {
            throw new HttpException('Нет загруженных данных');
        }

        $activationService = $this->get('bi.activation.service');
        // MOCK фильтр - временное решение
        $mockFilter = $activationService->mockQueryBuilder($activation);


        $params = $this->getParams($paramFetcher, 'data');
        $filter = new \BiBundle\Entity\Filter\Activation\Data($params);
        $backendService = $this->get('bi.backend.service');
        file_put_contents('/tmp/fiter', $mockFilter);
        $paramFilter = $filter->json ?: $mockFilter;
        $result = $backendService->getData($activation, $paramFilter);

        $view = $this->view($result);
        return $this->handleView($view);
    }

}