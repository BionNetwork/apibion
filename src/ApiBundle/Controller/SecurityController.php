<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\Exception\AuthenticateException;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use BiBundle\Form\Model\PasswordRecovery;
use BiBundle\Form\Model\Registration;
use BiBundle\Service\PasswordRecoveryService;
use BiBundle\Service\RegistrationService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SecurityController extends RestController
{
    /**
     * ### Minimal Response (e.g. anonymous) ###
     *
     *     {
     *       "data": {
     *         "token": <token>
     *       }
     *     }
     *
     * ### Failed Response ###
     *
     *     {
     *       "success": false
     *       "exception": {
     *         "code": <code>,
     *         "message": <message>
     *       }
     *     }
     *
     *
     * @ApiDoc(
     *  section="1. Аутентификация",
     *  resource=true,
     *  description="Аутентификация пользователя",
     *  statusCodes={
     *         200="При успешной аутентификации",
     *         401="Неверные данные для аутентификации",
     *         404="Пользователь не найден",
     *         400="Не указаны необходимые параметры запроса"
     *     },
     *  responseMap={
     *         401={
     *           "class"="ApiBundle\Service\Exception\AuthenticateException",
     *           "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-KEY",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     *
     * @RequestParam(name="login", description="Login")
     * @RequestParam(name="password", description="Password")
     *
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postLoginAction(ParamFetcher $paramFetcher)
    {
        $service = $this->get('api.auth_service');
        $keyProvider = $this->get('api.key_provider');

        $login = $paramFetcher->get('login');
        $password = $paramFetcher->get('password');
        $user = $service->authenticate($login, $password);
        $token = $keyProvider->generateToken($user);

        $data = [
            'token' => $token,
        ];

        $view = $this->view($data);

        return $this->handleView($view);
    }

}
