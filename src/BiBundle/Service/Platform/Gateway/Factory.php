<?php
/**
 * @package   BiBundle/Service/Platform
 */

namespace BiBundle\Service\Platform\Gateway;

use BiBundle\Service\Platform;
/**
 * Create Gateway objects
 */

class Factory
{
    /**
     * Create gateway
     *
     * @param $channelId
     * @return AbstractGateway
     * @throws Exception
     */
    public static function factory($channelId)
    {
        switch($channelId) {
            case 'main':
                return new Platform();
            default:
                throw new Exception("Unknown channel id " . $channelId);
        }
    }
}
