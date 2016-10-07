<?php
/**
 * @package   BiBundle/Service/Backend
 */

namespace BiBundle\Service\Backend\Gateway;

use BiBundle\Service\Backend;
/**
 * Gateway Interface
 */
interface GatewayInterface
{
    /**
     * Gateway name
     *
     * @return mixed
     */
    public function getName();

    /**
     * Send request
     *
     * @param Backend\Request $request
     * @return mixed
     */
    public function send(Backend\Request $request);
}
