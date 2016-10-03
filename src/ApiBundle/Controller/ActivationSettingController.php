<?php

namespace ApiBundle\Controller;

use BiBundle\Entity\Activation;
use BiBundle\Service\ActivationSettingService;
use BiBundle\Service\Exception\ActivationSettingException;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ActivationSettingController extends RestController
{
    /** @var  ActivationSettingService */
    private $service;

    /**
     * @param ActivationSettingService $service
     */
    public function __construct(ActivationSettingService $service)
    {
        $this->service = $service;
    }

    /**
     * @ApiDoc(
     *  section="4.1 Активации: настройки",
     *  description="Получение настроек активации",
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
        $settings = $this->service->getAll($activation);

        return $this->handleView($this->view($settings, 200));
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
     * @Post("/activation/{activation}/{key}", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function postSettingAction(Request $request, Activation $activation, $key)
    {
        if (!$value = $request->get('value')) {
            throw new HttpException(400);
        }
        try {
            $this->service->create($activation, $key, $value);
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
     * @Put("/activation/{activation}/{key}", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function putSettingAction(Request $request, Activation $activation, $key)
    {
        if (!$value = $request->get('value')) {
            throw new HttpException(400);
        }
        $this->service->update($activation, $key, $value);

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
     * @Post("/activation/{activation}/{key}/undo", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function postSettingUndoAction(Activation $activation, $key)
    {
        $setting = null;
        try {
            $setting = $this->service->undo($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(400, $e->getMessage());
        }

        return $this->handleView($this->view($setting, 204));
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
     * @Post("/activation/{activation}/{key}/redo", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function postSettingsRedoAction(Activation $activation, $key)
    {
        $setting = null;
        try {
            $setting = $this->service->redo($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(400, $e->getMessage());
        }

        return $this->handleView($this->view($setting, 204));
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
     * @Delete("/activation/{activation}/{key}", requirements={"key" = "\w+", "activation" = "\d+"})
     */
    public function deleteSettingAction(Activation $activation, $key)
    {
        try {
            $this->service->delete($activation, $key);
        } catch (ActivationSettingException $e) {
            throw new HttpException(404, $e->getMessage());
        }

        return $this->handleView($this->view(null, 204));
    }

}