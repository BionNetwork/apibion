<?php
/**
 * @package    ApiBundle\Controller
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;
use BiBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends RestController
{
    /**
     * ### Minimal Response ###
     *
     *      {
     *          "data":{
     *              "id":<user id>,
     *              "first_name":<user first name>,
     *              ...
     *          },
     *          "time":<time request>
     *      }
     *
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 404,
     *                  "message": "Not Found"
     *              },
     *              "errors": null
     *          }
     *      }
     *
     * @ApiDoc(
     *  section="Пользователи",
     *  resource=true,
     *  description="Получение информации о пользователе",
     *  statusCodes={
     *         200="При успешном ответе",
     *         404="Пользователь не найден"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access token header",
     *          "required"="true"
     *      }
     *    },
     *  output="\ApiBundle\Service\DataTransferObject\Object\UserValueObject"
     * )
     *
     * @Route(requirements={"user": "\d+"})
     *
     * @param User $user идентификатор пользователя
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction(User $user = null)
    {
        if (!$user) {
            throw $this->createNotFoundException("Пользователь не найден");
        }
        $transfer = $this->get('api.data_transfer_object.user_transfer_object');

        $view = $this->view($transfer->getObjectData($user));
        return $this->handleView($view);
    }

    /**
     * ### Minimal Response ###
     *
     *      {
     *      }
     *
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Validation Failed"
     *              },
     *              "errors": {
     *                  "user":{
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
     * @ApiDoc(
     *  section="Пользователи",
     *  resource=true,
     *  description="Редактирование пользователя (частичное обновление)",
     *  statusCodes={
     *         204="При успешном Обновлении",
     *         404="Пользователь не найден",
     *         400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access token header",
     *          "required"="true"
     *      }
     *    }
     * )
     *
     *
     * @RequestParam(name="birth_date", description="User birth date")
     * @RequestParam(name="email", requirements="\w+", description="User email")
     * @RequestParam(name="firstname", description="User first name")
     * @RequestParam(name="lastname", description="User last name")
     * @RequestParam(name="middlename", description="User middle name")
     * @RequestParam(name="login", description="User login")
     * @RequestParam(name="phone", description="User phone")
     * @RequestParam(name="position", description="User position")
     *
     * @Route(requirements={"user": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param User $user идентификатор пользователя
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchUsersAction(User $user, Request $request)
    {
        $form = $this->createForm(new \BiBundle\Form\Type\UserMainFields(), $user);

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }

        $userService = $this->get('user.service');
        $userService->save($user);
        $view = $this->view(null, 204);
        return $this->handleView($view);
    }

    /**
     * ### Minimal Response ###
     *
     *      {
     *      }
     *
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Validation Failed"
     *              },
     *              "errors": {
     *                  "user":{
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
     * @ApiDoc(
     *  section="Пользователи",
     *  resource=true,
     *  description="Редактирование пользователя",
     *  statusCodes={
     *         204="При успешном обновлении",
     *         404="Пользователь не найден",
     *         400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access token header",
     *          "required"="true"
     *      }
     *    },
     *  input={
     *      "class"="BiBundle\Form\Type\UserMainFields",
     *      "name"=""
     *  }
     * )
     *
     *
     * @Route(requirements={"user": "\d+"})
     *
     * @param User $user идентификатор пользователя
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putUsersAction(User $user, Request $request)
    {
        $form = $this->createForm('BiBundle\Form\Type\UserMainFields', $user);

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }

        $userService = $this->get('user.service');
        $userService->save($user);
        $view = $this->view(null, 204);
        return $this->handleView($view);
    }

    /**
     *
     * ### Failed Response ###
     *      {
     *          {
     *              "success": false,
     *              "exception": {
     *                  "code": 400,
     *                  "message": "Validation Failed"
     *              },
     *              "errors": {
     *                  "user":{
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
     * @ApiDoc(
     *  section="Пользователи",
     *  resource=true,
     *  description="Поиск пользователей",
     *  statusCodes={
     *         200="При успешном выполнении запроса",
     *         400="Ошибка при выполнении запроса",
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    },
     *  output="\ApiBundle\Service\DataTransferObject\Object\SearchUserValueObject"
     * )
     *
     * @QueryParam(name="query", allowBlank=true, description="Строка запроса")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersSearchAction(ParamFetcher $paramFetcher)
    {
        $transfer = $this->get('api.data_transfer_object.search_user_transfer_object');
        $userService = $this->get('user.service');

        $query = $this->getParam($paramFetcher, 'query', 'user');
        $foundData = $userService->search($query);
        $view = $this->view($transfer->getObjectData($foundData));
        return $this->handleView($view);
    }

}