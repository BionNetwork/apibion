<?php
/**
 * @package    BiBundle\Service
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service;

use BiBundle\Form\Model\PasswordRecovery;
use BiBundle\Service\Sms\Message;
use BiBundle\Service\Token\InvalidTokenException;
use BiBundle\Entity\User;
use BiBundle\Service\Token\TokenConfirmRequestInterface;
use BiBundle\Service\Token\TokenManagementService;

class PasswordRecoveryService extends TokenManagementService implements TokenConfirmRequestInterface
{
    const TOKEN_RECOVERY_SESSION = 'token:recovery:session';// token created to identify user requests
    const TOKEN_RECOVERY_REQUEST = 'token:recovery:request';
    const RECOVERY_REQUEST_TTL = 3600;// time to life for recovery key @todo use config for ttl settings

    /**
     * Support function that returns request token name
     *
     * @param $token
     * @return string
     */
    protected function getRequestTokenName($token)
    {
        return sprintf('%s:%s', self::TOKEN_RECOVERY_REQUEST, $token);
    }
    /**
     * Support function that returns session token name
     *
     * @param $token
     * @return string
     */
    protected function getSessionTokenName($token)
    {
        return sprintf('%s:%s', self::TOKEN_RECOVERY_SESSION, $token);
    }

    protected function getSessionTokenKey($token)
    {
        return $this->getSessionTokenName($token);
    }

    /**
     * Remembers token and sends registration request
     *
     * @param string $phone phone number to send key
     * @param string $token token saved in storage to identify user's requests
     * @return string key to register new user
     */
    public function makeRequest($phone, $token)
    {
        $key = $this->getRequestTokenName($token);
        $smsKey = $this->getRandomNumber(6, true);

        $storage = $this->getTokenStorage();
        $storage->set($key, json_encode(['code' => $smsKey, 'phone' => $phone]), self::RECOVERY_REQUEST_TTL);

        $messageText = $this->getMessage($smsKey);
        $messageService = $this->getMessageService();
        $message = new Message();
        $message->setPhone($phone);
        $message->setText($messageText);
        $messageService->send($message);

        return $smsKey;
    }

    /**
     * Register confirm request (after code was received)
     *
     * @param $token
     * @param array $data (['phone' => '<some phone>', 'code' => '<code received>'])
     * @return mixed
     * @throws InvalidTokenException
     */
    public function makeConfirmRequest($token, array $data)
    {
        $storage = $this->getTokenStorage();
        $token = $storage->getToken($token);
        $key = $this->getSessionTokenName($token);
        if (empty($data['phone']) || empty($data['code'])) {
            throw new InvalidTokenException("Request confirmation does not hold <phone> or <code> parameters");
        }
        $data['token'] = $token;

        $storage->set($key, json_encode($data), self::RECOVERY_REQUEST_TTL);
        return $token;
    }

    /**
     * @param User $user
     * @param PasswordRecovery $recovery
     * @return User
     */
    public function recoverPassword(User $user, PasswordRecovery $recovery)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user->setPassword($recovery->getPassword());

        $em->flush();

        return $user;
    }

    /**
     * Registration message
     *
     * @param $value
     * @return string
     */
    protected function getMessage($value)
    {
        return sprintf('Для восстановления пароля введите код %s', $value);
    }
}