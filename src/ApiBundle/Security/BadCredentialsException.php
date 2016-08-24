<?php
/**
 * @package    ApiBundle\Security
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Security;

class BadCredentialsException extends \Symfony\Component\Security\Core\Exception\BadCredentialsException
{
    protected $messageKey;

    public function __construct($message = null)
    {
        if (null === $message) {
            $this->messageKey = parent::getMessageKey();
        } else {
            $this->messageKey = $message;
        }
    }

    public function getMessageKey()
    {
        return $this->messageKey;
    }
}