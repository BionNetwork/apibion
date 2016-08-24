<?php
/**
 * @package    BiBundle\Service\Token
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Token;

interface TokenConfirmRequestInterface
{
    /**
     * Remembers token and sends registration request
     *
     * @param string $phone phone number to send key
     * @param string $token token saved in storage to identify user's requests
     * @return string key to register new user
     */
    public function makeRequest($phone, $token);

    /**
     * Register confirm request (after code was received)
     *
     * @param $token
     * @param array $data (['phone' => '<some phone>', 'code' => '<code received>'])
     * @return mixed
     * @throws InvalidTokenException
     */
    public function makeConfirmRequest($token, array $data);
}