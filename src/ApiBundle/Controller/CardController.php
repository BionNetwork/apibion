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

class CardController extends RestController
{

    /**
     * @ApiDoc(
     *  section="2. Магазин",
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
     *      }
     *    }
     * )
     *
     * @QueryParam(name="id", allowBlank=true, requirements="\d+", description="Идентификатор карточки")
     * @QueryParam(name="limit", default="20", requirements="\d+", description="Количество запрашиваемых проектов" )
     * @QueryParam(name="offset", nullable=true, requirements="\d+", description="Смещение, с которого нужно начать просмотр" )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getCardsAction(ParamFetcher $paramFetcher)
    {
        $cardService = $this->get('bi.card.service');
        $params = $this->getParams($paramFetcher, 'card');
        $filter = new \BiBundle\Entity\Filter\Card($params);
        $cards = $cardService->getByFilter($filter);
        $service = $this->get('api.data.transfer_object.card_transfer_object');
        $view = $this->view($service->getObjectListData($cards));
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="2. Магазин",
     *  resource=true,
     *  description="Аргументы",
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
     * @Route("/card/{card}/arguments", requirements={"card": "\d+"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getCardArgumentsAction(\BiBundle\Entity\Card $card)
    {
        $argumentRepository = $this->get('repository.argument_repository');
        $arguments = $argumentRepository->findBy(['card' => $card]);
        $argumentListArray = [];
        foreach ($arguments as $argument) {
            $argumentListArray[] = $argument;
        }

        $service = $this->get('api.data.transfer_object.argument_transfer_object');
        $view = $this->view($service->getObjectListData($arguments));
        return $this->handleView($view);

        $view = $this->view($result);
        return $this->handleView($view);
    }

}