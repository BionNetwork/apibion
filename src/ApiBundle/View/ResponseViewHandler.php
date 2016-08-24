<?php
/**
 * @package    ApiBundle\View
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\View;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Custom JSON response handler
 */
class ResponseViewHandler
{
    /**
     * @param ViewHandler $handler
     * @param View        $view
     * @param Request     $request
     * @param string      $format
     *
     * @return JsonResponse
     */
    public function createResponse(ViewHandler $handler, View $view, Request $request, $format)
    {
        if ($view->getStatusCode() !== 200) {
            return new JsonResponse($view->getData(), $view->getStatusCode(), $view->getHeaders());
        }

        $time = $request->server->get('REQUEST_TIME_FLOAT', microtime(true));
        $elapsed = microtime(true) - $time;
        // only for 200 responses
        $data = ['data' => $view->getData(), 'time' => round($elapsed, 5)];
        return new JsonResponse($data, $view->getStatusCode(), $view->getHeaders());
    }
}