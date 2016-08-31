<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend\Gateway;

use BiBundle\Service\Backend;
/**
 * Gateway Interface
 */
interface IGateway
{
    /**
     * Gateway name
     *
     * @return mixed
     */
    public function getName();

    /**
     * Send Platform
     *
     * @param Backend\Message $message
     * @return mixed
     */
    public function send(Backend\Message $message);
}
