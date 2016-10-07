<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\DataTransferObject\ActivationSettingTransferObject;
use BiBundle\Entity\Activation;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\Exception\ActivationSettingException;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ActivationSettingController extends RestController
{
    /** @var  ActivationSettingService */
    private $service;

    /**
     * @param ActivationSettingService $service
     */
    public function __construct(Container $container, ActivationSettingService $service)
    {
        $this->container = $container;
        $this->service = $service;
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Получение настроек активации",
     *  resource=true,
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @Get("/activation/{activation}/settings");
     */
    public function getSettingsAction(Activation $activation)
    {
        $this->denyAccessUnlessGranted('view', $activation);
        $settings = $this->service->getAll($activation);

        return $this->handleView($this->view(ActivationSettingTransferObject::getObjectListData($settings), 200));
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Получение определенной настройки",
     *  resource=true,
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @Get("/activation/{activation}/setting/{key}");
     */
    public function getSettingAction(Activation $activation, $key)
    {
        $this->denyAccessUnlessGranted('view', $activation);
        try {
            $setting = $this->service->get($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $this->handleView($this->view(ActivationSettingTransferObject::getObjectData($setting), 200));
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Создание настройки для активации",
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     * @RequestParam(name="value", nullable=false, description="Значение настройки")
     * @Post("/activation/{activation}/setting/{key}", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function postSettingAction(ParamFetcher $paramFetcher, Activation $activation, $key)
    {
        $this->denyAccessUnlessGranted('edit', $activation);
        try {
            $this->service->create($activation, $key, $paramFetcher->get('value'));
        } catch (ActivationSettingException $e) {
            throw new HttpException(409, $e->getMessage());
        }

        return $this->handleView($this->view(null, 204));
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Присвоение нового значения настройке",
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     * @RequestParam(name="value", nullable=false, description="Новой значение настройки")
     * @Put("/activation/{activation}/setting/{key}", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function putSettingAction(ParamFetcher $paramFetcher, Request $request, Activation $activation, $key)
    {
        $this->denyAccessUnlessGranted('edit', $activation);
        $this->service->update($activation, $key, $paramFetcher->get('value'));

        return $this->handleView($this->view(null, 204));
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Отмена присвоения значения настройке",
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     * @Post("/activation/{activation}/setting/{key}/undo", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function postSettingUndoAction(Activation $activation, $key)
    {
        $this->denyAccessUnlessGranted('edit', $activation);
        $setting = null;
        try {
            $setting = $this->service->undo($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(400, $e->getMessage());
        }

        return $this->handleView($this->view(ActivationSettingTransferObject::getObjectData($setting), 200));
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Восстановление отмены присвоения значения настройке",
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     * @Post("/activation/{activation}/setting/{key}/redo", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function postSettingsRedoAction(Activation $activation, $key)
    {
        $this->denyAccessUnlessGranted('edit', $activation);
        $setting = null;
        try {
            $setting = $this->service->redo($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(400, $e->getMessage());
        }

        return $this->handleView($this->view(ActivationSettingTransferObject::getObjectData($setting), 200));
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Удаление настройки и ее истории",
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     * @Delete("/activation/{activation}/setting/{key}", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function deleteSettingAction(Activation $activation, $key)
    {
        $this->denyAccessUnlessGranted('edit', $activation);
        try {
            $this->service->delete($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $this->handleView($this->view(null, 204));
    }

}