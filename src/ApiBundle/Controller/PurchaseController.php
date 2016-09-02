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

class PurchaseController extends RestController
{

    /**
     * @ApiDoc(
     *  section="3. Покупки",
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
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getPurchasesAction()
    {
        $purchases = $this->getUser()->getPurchase();
        $data = [];
        foreach ($purchases as $purchase) {
            $data[] = $purchase;
        }
        $service = $this->get('api.data.transfer_object.purchase_transfer_object');
        $view = $this->view($service->getObjectListData($data));
        return $this->handleView($view);
    }


    /**
     * @ApiDoc(
     *  section="3. Покупки",
     *  resource=true,
     *  description="Покупка карточки",
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
     * @Route("/purchase/card/{card}", requirements={"card": "\d+"})
     *
     * @QueryParam(name="price", allowBlank=true, requirements="\d+", description="Стоимость карточки в интерфейсе")
     *
     * @param \BiBundle\Entity\Card $card
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function postCardPurchaseAction(\BiBundle\Entity\Card $card, ParamFetcher $paramFetcher)
    {
        $purchaseService = $this->get('bi.purchase.service');

        $purchase = $purchaseService->purchase($card);

        $data = [
            'id' => $purchase->getId()
        ];

        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  section="3. Покупки",
     *  resource=true,
     *  description="Активация карточки",
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
     * @Route("/purchase/activate/{purchase}", requirements={"purchase": "\d+"})
     *
     * @param \BiBundle\Entity\Purchase $purchase
     * @return Response
     */
    public function postPurchaseActivateAction(\BiBundle\Entity\Purchase $purchase)
    {
        $purchaseService = $this->get('bi.purchase.service');

        $activation = $purchaseService->activate($purchase);

        $data = [
            'id' => $activation->getId()
        ];

        $view = $this->view($data);
        return $this->handleView($view);
    }

}