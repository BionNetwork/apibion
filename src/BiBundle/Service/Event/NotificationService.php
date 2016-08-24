<?php
/**
 * @package    BiBundle\Service\Event
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Event;

use BiBundle\Repository\Document\NotificationRepository;
use BiBundle\Repository\Document\RequestRepository;

/**
 * Notification service is used to deal with user requests actions and notification handling
 * Main part of work for registering events in queue and persistent storage is delegated to
 * appropriate repositories and queue management system
 */
class NotificationService
{
    /**
     * @var RequestRepository
     */
    private $requestRepository;
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    public function __construct(RequestRepository $requestRepository, NotificationRepository $notificationRepository)
    {
        $this->requestRepository = $requestRepository;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Repository for persisting requests
     *
     * @return RequestRepository
     */
    public function getRequestRepository()
    {
        return $this->requestRepository;
    }

    /**
     * Repository for persisting notifications
     *
     * @return NotificationRepository
     */
    public function getNotificationRepository()
    {
        return $this->notificationRepository;
    }
}