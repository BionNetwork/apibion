<?php
/**
 * @package    ApiBundle\Controller
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BiBundle\Entity\User;
use BiBundle\Form\Model\ChangePassword;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use BiBundle\Service\Upload\FilePathStrategy;

class ProfileController extends RestController
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
     *              "id":<user id>
     *          },
     *          "time":<time request>
     *      }
     *
     * @ApiDoc(
     *  section="Профиль",
     *  resource=true,
     *  description="Профиль пользователя",
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
     *  output="\ApiBundle\Service\DataTransferObject\Object\UserValueObject"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProfileAction()
    {
        $user = $this->getUser();
        $service = $this->get('api.data_transfer_object.user_transfer_object');

        $view = $this->view($service->getObjectData($user));
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
     *  section="Профиль",
     *  resource=true,
     *  description="Редактирование пользователя",
     *  statusCodes={
     *         204="При успешном обновлении",
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
     *      "class"="BiBundle\Form\Type\Profile",
     *      "name"=""
     *  }
     * )
     *
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putProfileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm('BiBundle\Form\Type\Profile', $user);

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
     *                  "password_change": {
     *                       "children": {
     *                          "identifier": {
     *                              "errors": [
     *                                  <errorMessage 1>,
     *                                  <...>,
     *                                  <errorMessage N>
     *                              ]
     *                          }
     *                      }
     *                  }
     *              }
     *          }
     *      }
     *
     * @ApiDoc(
     *  section="Профиль",
     *  resource=true,
     *  description="Изменение пароля",
     *  statusCodes={
     *         204="При успешном обновлении",
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
     *      "class"="BiBundle\Form\UserChangePasswordType",
     *      "name"=""
     *  }
     * )
     *
     * Change user password
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws HttpException
     */
    public function putProfileChangepasswordAction(Request $request)
    {
        $password = new ChangePassword();
        $form = $this->createForm('BiBundle\Form\UserChangePasswordType', $password);
        $this->processForm($request, $form);
        /** @var \BiBundle\Entity\User $user */
        $user = $this->getUser();

        $userService = $this->get('user.service');

        if (!$userService->isPasswordValid($user, $password->getOldPassword())) {
            $this->addFormError($form, 'oldPassword', 'Старый пароль введен не верно');
        }

        if (!$form->isValid()) {
            throw $this->createFormValidationException($form);
        }

        $user->setPassword($password->getPassword());
        $userService->save($user);

        $view = $this->view(null, 204);
        return $this->handleView($view);
    }


    /**
     *
     *
     * @ApiDoc(
     *  section="Профиль",
     *  resource=true,
     *  description="Загрузка фото пользователя",
     *  statusCodes={
     *          204="Успех",
     *          404="Пользователь не найден",
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
     * @Route(requirements={"user": "\d+"})
     *
     * @RequestParam(name="original", description="Оригинал фотографии", nullable=false)
     * @RequestParam(name="resized", description="Превью фотографии", nullable=false)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postProfileUploadphotoAction(Request $request)
    {
        $user = $this->getUser();

        $imageBig = $request->files->get('original');
        if (!$imageBig) {
            throw new HttpException(400, 'Оригинальная картинка не найдена');
        }

        $imageSmall = $request->files->get('resized');
        if (!$imageSmall) {
            throw new HttpException(400, 'Маленькая картинка не найдена');
        }

        $imageService = $this->get('images.service');
        $originalImage = $imageService->createImageFromFile($imageBig);
        $resizedImage = $imageService->createImageSizeFromFile($imageSmall, [
            'width' => 300,
            'height' => 300
        ]);

        /**
         * Загружаем фото
         */
        $strategy = new FilePathStrategy();
        $strategy->setEntity($user);
        $uploadService = $this->get('file.upload_profile_photo');
        $uploadService->setUploadStrategy($strategy);
        $uploadedOriginalPathArray = $uploadService->upload($imageBig);
        $uploadedResizedPathArray = $uploadService->upload($imageSmall, [
            'name' => $imageService->getImageSizeName($uploadedOriginalPathArray['path'], $resizedImage)
        ]);

        // сохраняем фото в БД
        $resizedImage->setPath($uploadedResizedPathArray['path']);
        $originalImage->setPath($uploadedOriginalPathArray['path']);
        $originalImage->addSize($resizedImage);
        $originalImage->setSize($imageBig->getClientSize());
        $imageService->save($originalImage);

        // Привязываем фото к пользователю (также см. BiBundle\Entity\Listener\UserListener)
        $userService = $this->get('user.service');
        $user->setAvatar(new File($uploadedOriginalPathArray['full_path'], false));
        $user->setAvatarSmall(new File($uploadedResizedPathArray['full_path'], false));
        $userService->save($user);

        $view = $this->view(null, 204);
        return $this->handleView($view);
    }
}