<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends RestController
{
    /**
     *
     * @ApiDoc(
     *  section="Конфигурация",
     *  resource=true,
     *  description="Локализованые строки для UI",
     *  statusCodes={
     *         200="При успешном запросе",
     *         400="Ошибка запроса"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      },
     *      {
     *          "name"="Accept-Language",
     *          "description"="strings locale language",
     *          "required"=true
     *      }
     *   }
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getConfigStringsAction(Request $request)
    {
        return $this->handleView(
            $this->view(
                $this->get('api.config.service')->getUiStrings($request->getPreferredLanguage())
            )
        );
    }
}