<?php

namespace BiBundle\Controller;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BiBundle\Entity\Image;
use BiBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use BiBundle\Service\Upload\FilePathStrategy;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('BiBundle:User')->findAll();

        return $this->render('BiBundle:user:index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('BiBundle\Form\UserType', $user, [
            'validation_groups' => ['registration', 'Default']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService = $this->get('user.service');
            $userService->save($user);

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('BiBundle:user:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('BiBundle:user:show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $rootPath = $this->getParameter('upload_dir');
        // @todo make better solution, leave for now
        if ($request->getMethod() == 'POST') {
            if ($user->getAvatar()) {
                $user->setAvatar($rootPath . $user->getAvatar());
            }
            if ($user->getAvatarSmall()) {
                $user->setAvatarSmall($rootPath . $user->getAvatarSmall());
            }
        }
        $editForm = $this->createForm('BiBundle\Form\UserType', $user);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /**
             * Загружаем фото
             */
            $uploadedFile = $user->getAvatar();

            if ($uploadedFile instanceof UploadedFile) {
                $user->setAvatar(null);
                $strategy = new FilePathStrategy();
                $strategy->setEntity($user);
                $uploadService = $this->get('file.upload_profile_photo');
                $uploadService->setUploadStrategy($strategy);
                $uploadedOriginalPathArray = $uploadService->upload($uploadedFile);

                // сохраняем фото в БД
                $imageService = $this->get('images.service');
                $originalImage = $imageService->createImageFromFile($uploadedFile);
                $originalImage->setPath($uploadedOriginalPathArray['path']);
                $originalImage->setSize($uploadedFile->getClientSize());
                $imageService->save($originalImage);

                $user->setAvatar(new File($uploadedOriginalPathArray['full_path']));
                $user->setAvatarSmall($user->getAvatar());// @todo resize image
            }

            $userService = $this->get('user.service');
            $userService->save($user);
            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }
        return $this->render('BiBundle:user:edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function profileAction(Request $request, User $user)
    {
        $form = $this->createForm('BiBundle\Form\ProfileType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_profile', array('id' => $user->getId()));
        }

        return $this->render('BiBundle:user:profile.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Возвращает всех пользователей для выбора ответственного проекта
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $query = ($request->get('q'));
        $userList = $this->getDoctrine()->getRepository('BiBundle:User')->findUser($query);
        $resultArray = [];
        /* @var $user User */
        foreach ($userList as $user) {
            $element = [
                'id' => $user->getId(),
                'text' => $user->getFirstname(),
            ];
            $resultArray[] = $element;
        }
        return new JsonResponse($resultArray);
    }
}
