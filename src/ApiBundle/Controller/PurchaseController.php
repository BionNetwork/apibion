<?php

namespace ApiBundle\Controller;

use BiBundle\Service\Backend\Exception;
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
     *  description="Получение купленных карточек по фильтру",
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
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Validation Failed"
     *              },
     *              "errors": {
     *                  "purchase":{
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
     * ### Success Response ###
     *      {
     *          "data":{
     *              "id":<new purchase id>
     *          },
     *          "time":<time request>
     *      }
     *
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
     * ),
     *  input={
     *      "class"="BiBundle\Form\PurchaseType",
     *      "name"=""
     *  }
     *
     * @RequestParam(name="card", requirements="\d+", allowBlank=false, nullable=false, description="Карточка для покупки")
     * @param Request $request
     * @return Response
     */
    public function postPurchaseAction(Request $request)
    {
        $purchaseService = $this->get('bi.purchase.service');
        $form = $this->createForm('BiBundle\Form\PurchaseType');

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }

        $purchase = $purchaseService->save($form->getData());

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
     *  description="Активация купленной карточки",
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
     * @Route(requirements={"purchase": "\d+"})
     *
     * @param \BiBundle\Entity\Purchase $purchase
     * @return Response
     */
    public function postPurchaseActivationAction(\BiBundle\Entity\Purchase $purchase)
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