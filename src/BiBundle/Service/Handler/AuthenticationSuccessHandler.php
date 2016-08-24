<?php
/**
 * @package    BiBundle\Service\Handler
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(HttpUtils $httpUtils, ContainerInterface $container, array $options)
    {
        parent::__construct($httpUtils, $options);
        $this->container = $container;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var \BiBundle\Entity\User $user */
        $user = $token->getUser();
        $user->setLastLoginOn(new \DateTime());

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
    }
}