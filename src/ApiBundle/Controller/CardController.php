<?php

namespace ApiBundle\Controller;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCategory;
use BiBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class CardController extends RestController
{

    /**
     * @ApiDoc(
     *  section="2. Карточки",
     *  resource=true,
     *  description="Получение списка карточек по фильтру",
     *  statusCodes={
     *         200="При успешном получении данных",
     *         400="Ошибка получения данных"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      },
     *      {
     *          "name"="Accept-Language",
     *          "description"="translation language",
     *          "required"=false
     *      }
     *    }
     * )
     *
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор карточки")
     * @QueryParam(name="limit", default="20", requirements="\d+", description="Количество запрашиваемых проектов" )
     * @QueryParam(name="offset", nullable=true, requirements="\d+", description="Смещение, с которого нужно начать просмотр" )
     * @QueryParam(name="category_id", nullable=true, requirements="\d+", description="Категория карточки" )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getCardsAction(ParamFetcher $paramFetcher)
    {
        $cardService = $this->get('bi.card.service');
        $params = $this->getParams($paramFetcher, 'card');
        $filter = new \BiBundle\Entity\Filter\Card($params);
        $filter->user_id = $this->getUser()->getId();
        $cards = $cardService->getByFilter($filter);
        $service = $this->get('api.data.transfer_object.card_transfer_object');
        $view = $this->view($service->getObjectListData($cards));
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="2. Карточки",
     *  description="Получение информации по карточке",
     *  statusCodes={
     *          200="Успех",
     *          400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      },
     *      {
     *          "name"="Accept-Language",
     *          "description"="translation language",
     *          "required"=false
     *      }
     *    }
     * )
     *
     * @Route(requirements={"card": "\d+"})
     *
     * @param Card $card
     * @return Response
     * @internal param Request $request
     *
     */
    public function getCardAction(Card $card)
    {
        $dto = $this->get('api.data.transfer_object.card_transfer_object');
        $data = $dto->getObjectData($card);
        return $this->handleView($this->view($data));
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="2. Карточки",
     *  description="Получение списка категорий по фильтру",
     *  statusCodes={
     *          200="Успех",
     *          400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      },
     *      {
     *          "name"="Accept-Language",
     *          "description"="translation language",
     *          "required"=false
     *      }
     *    }
     * )
     *
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор категории")
     * @QueryParam(name="limit", default="20", requirements="\d+", description="Количество запрашиваемых категорий" )
     * @QueryParam(name="offset", nullable=true, requirements="\d+", description="Смещение, с которого нужно начать просмотр" )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getCardsCategoriesAction(ParamFetcher $paramFetcher)
    {
        $cardCategoryService = $this->get('bi.card_category.service');
        $params = $this->getParams($paramFetcher, 'card');
        $filter = new \BiBundle\Entity\Filter\CardCategory($params);

        /** @var CardCategory[] $categories */
        $categories = $cardCategoryService->getByFilter($filter);

        $service = $this->get('api.data.transfer_object.card_category_transfer_object');
        $view = $this->view($service->getObjectListData($categories));
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="2. Карточки",
     *  description="Получение списка карточек сгруппированных по категориям",
     *  statusCodes={
     *         200="При успешном получении данных",
     *         400="Ошибка получения данных"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      },
     *      {
     *          "name"="Accept-Language",
     *          "description"="translation language",
     *          "required"=false
     *      }
     *    }
     * )
     *
     * @return Response
     */
    public function getCardsCategorizedAction()
    {
        $cards = $this->get('bi.card.service')->getAllCards();
        $view = $this->view(
            $this->get('api.data.transfer_object.card_transfer_object')->getObjectListDataCategorized($cards)
        );
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="2. Карточки",
     *  description="Получение купленной карточки",
     *  statusCodes={
     *          200="Успех",
     *          403="Карточка не куплена"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      },
     *      {
     *          "name"="Accept-Language",
     *          "description"="translation language",
     *          "required"=false
     *      }
     *    }
     * )
     *
     * @Route(requirements={"card": "\d+"})
     *
     * @param Card $card
     * @return Response
     * @internal param Request $request
     *
     */
    public function getCardPurchasedAction(Card $card)
    {
        if(!$this->get('bi.purchase.service')->isPurchased($card, $this->getUser())) {
            return $this->handleView($this->view('Card is not purchased', 403));
        }
        return $this->handleView($this->view(
            $this->get('api.data.transfer_object.card_transfer_object')->getObjectData($card))
        );
    }
}