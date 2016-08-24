<?php
/**
 * @package   BiBundle/Service/Platform
 * @author    miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
/**
 * @namespace
 */
namespace BiBundle\Service\Platform\Gateway;

use BiBundle\Service\Platform;
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
     * @param Platform\Message $message
     * @return mixed
     */
    public function send(Platform\Message $message);
}
