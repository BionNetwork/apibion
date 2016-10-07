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
     * @Route(requirements={"activation": "\d+", "dashboard": "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postActivationsDashboardAction(\BiBundle\Entity\Activation $activation, \BiBundle\Entity\Dashboard $dashboard)
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
     *  description="Получение активаций пользователя по фильтру",
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
     * @Route("/activations/{activation}/data", requirements={"activation": "\d+"})
     *
     * @return Response
     */
    public function postActivationDataAction(\BiBundle\Entity\Activation $activation)
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
     * @Route(requirements={"activation": "\d+"})
     *
     * @param \BiBundle\Entity\Activation $activation
     * @return Response
     */
    public function getActivationsFiltersAction(\BiBundle\Entity\Activation $activation)
    {
        $backendService = $this->get('bi.backend.service');
        $result = $backendService->getFilters($activation);

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
     * @Route("/activations/{activation}/data", requirements={"activation": "\d+"})
     *
     * @QueryParam(name="json", allowBlank=true, requirements=".+", description="Сериализованный в JSON фильтр")
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getActivationsDataAction(\BiBundle\Entity\Activation $activation, ParamFetcher $paramFetcher)
    {
        $activationStatusCode = $activation->getActivationStatus()->getCode();
        if(ActivationStatus::STATUS_ACTIVE !== $activationStatusCode) {
            throw new HttpException('Нет загруженных данных');
        }

        $activationService = $this->get('bi.activation.service');

        // MOCK фильтр - временное решение
        $mockFilter = $activationService->mockQueryBuilder($activation);

        $params = $this->getParams($paramFetcher, 'data');
        $filter = new \BiBundle\Entity\Filter\Activation\Data($params);
        $backendService = $this->get('bi.backend.service');
        $paramFilter = $filter->json ?: $mockFilter;
        $result = $backendService->getData($activation, $paramFilter);

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
     * @Route("/activations/{activation}/empty_filters", requirements={"activation": "\d+"})
     *
     *
     * @param \BiBundle\Entity\Activation $activation
     * @return Response
     */
    public function getEmptyFilterAction(\BiBundle\Entity\Activation $activation)
    {
        $activationStatusCode = $activation->getActivationStatus()->getCode();
        if(ActivationStatus::STATUS_ACTIVE !== $activationStatusCode) {
            throw new HttpException('Нет загруженных данных');
        }

        $activationService = $this->get('bi.activation.service');

        // MOCK фильтр - временное решение
        $mockFilter = $activationService->mockQueryBuilder($activation);

        $view = $this->view(json_decode($mockFilter, JSON_UNESCAPED_UNICODE));
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Создание связи Активация+Аргумент+Источник+Таблица+Столбец",
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
     * @Route(requirements={"activation": "\d+", "resource": "\d+", "argument": "\d+"})
     *
     * @RequestParam(name="table_name", allowBlank=false, requirements=".+", description="Наименование таблицы")
     * @RequestParam(name="column_name", allowBlank=false, requirements=".+", description="Уникальное наименование столбца")
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param \BiBundle\Entity\Resource $resource
     * @param \BiBundle\Entity\Argument $argument
     * @param ParamFetcher $paramFetcher
     *
     * @return Response
     */

    public function postActivationsResourceArgumentAction(
        \BiBundle\Entity\Activation $activation,
        \BiBundle\Entity\Resource $resource,
        \BiBundle\Entity\Argument $argument,
        ParamFetcher $paramFetcher)
    {
        $params = $this->getParams($paramFetcher, 'ArgumentBond');

        $argumentBond = new \BiBundle\Entity\ArgumentBond();
        $argumentBond->setActivation($activation);
        $argumentBond->setResource($resource);
        $argumentBond->setArgument($argument);
        $argumentBond->setTableName($params['table_name']);
        $argumentBond->setColumnName($params['column_name']);

        $argumentBondRepository = $this->get('bi.argument_bond.service');
        $argumentBondRepository->save($argumentBond);

        $data = [
            'id' => $argumentBond->getId()
        ];
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="4. Активации",
     *  resource=true,
     *  description="Получение связи аргумент->данные",
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
     * @Route(requirements={"activation": "\d+"})
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return Response
     */
    public function getActivationsBondsAction(\BiBundle\Entity\Activation $activation)
    {
        $argumentBondService = $this->get('bi.argument_bond.service');
        $argumentBondList = $argumentBondService->getArgumentBondList($activation);
        $data = [];
        if($argumentBondList) {
            foreach ($argumentBondList as $argument) {
                $data[] = [
                    'argument' => $argument->getArgument()->getId(),
                    'resource' => $argument->getResource()->getId(),
                    'table_name' => $argument->getTableName(),
                    'column_name' => $argument->getColumnName()
                ];
            }
        }

        $view = $this->view($data);
        return $this->handleView($view);
    }

}