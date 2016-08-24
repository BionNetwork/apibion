<?php
/**
 * @package    ApiBundle\Security
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'success' => false,
            'exception' => [
                'code' => 403,
                'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
            ]
        ];
        return new JsonResponse(
            $data,
            403
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s given)',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();
        $keyName = $userProvider->getApiKey($apiKey);

        if (!$keyName) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('API key "%s" does not exist.', $apiKey)
            );
        }

        $user = $userProvider->loadUserByUsername($keyName);

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() == $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get('X-AUTHORIZE-KEY');

        if (!$apiKey) {
            throw new BadCredentialsException("No API key provided");
        }

        return new PreAuthenticatedToken(
            'access_key',
            $apiKey,
            $providerKey
        );
    }

}