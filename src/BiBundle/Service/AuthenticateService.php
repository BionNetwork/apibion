<?php
/**
 * @package    BiBundle\Service
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\SecurityEvents;
use BiBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticateService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Authenticate user
     *
     * @param Request $request
     * @param User $user
     */
    public function authenticate(Request $request, User $user)
    {
        // authenticate user
        $token = new UsernamePasswordToken($user, null, 'db_provider', $user->getRoles());
        $this->getContainer()->get('security.token_storage')->setToken($token);

        // notify all listeners
        $loginEvent = new InteractiveLoginEvent($request, $token);
        $this->getContainer()->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $loginEvent);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}