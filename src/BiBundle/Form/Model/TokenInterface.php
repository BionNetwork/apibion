<?php
/**
 * @package    BiBundle\Form\Model
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form\Model;

interface TokenInterface
{
    /**
     * Get token
     *
     * @return mixed
     */
    public function getToken();

    /**
     * Set token
     *
     * @param mixed $token
     * @return mixed
     */
    public function setToken($token);
}