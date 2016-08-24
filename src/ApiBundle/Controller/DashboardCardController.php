<?php

namespace ApiBundle\Controller;

use BiBundle\Entity\DashboardCard;
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
use BiBundle\Service\Upload\FilePathStrategy;

class DashboardCardController extends RestController
{

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
     *              "id":<dashboard card id>
     *          },
     *          "time":<time request>
     *      }
     *
     * @ApiDoc(
     *  section="Карточки (рабочие столы)",
     *  resource=true,
     *  description="Карточка (рабочие столы)",
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
     *  output="\ApiBundle\Service\DataTransferObject\Object\DashboardCardValueObject"
     * )
     *
     * @param \BiBundle\Entity\DashboardCard $dashboardCard
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDashboardCardAction(\BiBundle\Entity\Dashboard $dashboard, \BiBundle\Entity\DashboardCard $dashboardCard)
    {
        $service = $this->get('api.data.transfer_object.dashboard_card_transfer_object');
        $view = $this->view($service->getObjectData($dashboardCard));
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
     *  section="Карточки (рабочие столы)",
     *  resource=true,
     *  description="Создание экземпляра карточки на рабочем столе",
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
     * @param \BiBundle\Entity\Card $card
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDashboardsCardAction(\BiBundle\Entity\Dashboard $dashboard, \BiBundle\Entity\Card $card, Request $request)
    {
        $dashboardCardService = $this->get('bi.dashboard_card.service');
        $dashboardCard = new DashboardCard();

        $dashboardCard->setCard($card);
        $dashboardCard->setDashboard($dashboard);

        $form = $this->createForm(\BiBundle\Form\DashboardCardType::class, $dashboardCard);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }
        $dashboardCardService->save($dashboardCard);
        $data = [
            'id' => $dashboardCard->getId()
        ];
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="Карточки (рабочие столы)",
     *  resource=true,
     *  description="Получение карточек по фильтру",
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
    public function getDashboardCardsAction(\BiBundle\Entity\Dashboard $dashboard, ParamFetcher $paramFetcher)
    {
        $cardService = $this->get('bi.card.service');
        $params = $this->getParams($paramFetcher, 'dashboard');
        $filter = new \BiBundle\Entity\Filter\Card($params);
        $cards = $cardService->getByFilter($filter);
        $service = $this->get('api.data.transfer_object.card_transfer_object');
        $view = $this->view($service->getListData($cards));
        return $this->handleView($view);
    }

}