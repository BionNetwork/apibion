<?php
/**
 * @package    BiBundle\Service\Utils
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Utils;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Url based on host
 */
class HostBasedUrl
{
    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * Get url
     *
     * @param $value
     * @return null|string
     */
    public function getUrl($value)
    {
        if (!empty($value)) {
            return sprintf("%s://%s/%s",
                $this->getRequest()->getCurrentRequest()->getScheme(),
                $this->getRequest()->getCurrentRequest()->getHttpHost(),
                ltrim($value, '/')
            );
        }
        return null;
    }

    /**
     * @return RequestStack
     */
    public function getRequest()
    {
        return $this->request;
    }
}