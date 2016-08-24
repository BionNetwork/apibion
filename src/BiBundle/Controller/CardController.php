<?php
/**
 * Created by PhpStorm.
 * User: imnareznoi
 * Date: 04.08.16
 * Time: 11:43
 */

namespace BiBundle\Controller;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BiBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use BiBundle\Service\Upload\FilePathStrategy;

/**
 * Card controller.
 *
 */
class CardController extends Controller
{
    /**
     * Lists all card entities.
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
}