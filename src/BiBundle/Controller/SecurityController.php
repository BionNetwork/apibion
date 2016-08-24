<?php
/**
 * @package    BiBundle\Controller
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Controller;

use BiBundle\BiBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use BiBundle\Entity\User;
use BiBundle\Entity\UserStatus;
use BiBundle\Form\Registration as Registration;
use BiBundle\Service\Token\InvalidTokenException;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm('BiBundle\Form\Login');

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form->handleRequest($request);

        return $this->render('BiBundle:security:login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    public function registerAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('task_homepage');
        }

        $user = new User();
        $form = $this->createForm(Registration::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $userStatus = $em->getRepository('BiBundle:UserStatus')->findOneBy(['code' => 'active']);
            $userRole = $em->getRepository('BiBundle:UserRole')->findOneBy(['name' => 'active']);
            $user->setStatus($userStatus);
            $user->setRole($userRole);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('task_homepage');
        }

        return $this->render(
            'BiBundle:security:register.html.twig',
            array('form' => $form->createView())
        );

    }
}