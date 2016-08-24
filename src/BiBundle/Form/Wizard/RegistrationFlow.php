<?php
/**
 * @package    BiBundle\Form\Wizard
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Form\Wizard;

use Craue\FormFlowBundle\Event\PostBindRequestEvent;
use Craue\FormFlowBundle\Event\PostValidateEvent;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowEvents;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use BiBundle\Service\RegistrationService;
use BiBundle\Service\Token\InvalidTokenException;

/**
 * Registration Multiple Steps Form
 *
 * @link https://github.com/craue/CraueFormFlowBundle
 */
class RegistrationFlow extends FormFlow implements EventSubscriberInterface
{
    protected $revalidatePreviousSteps = false;
    /**
     * Register request key
     *
     * @var string
     */
    protected $key;
    /**
     * Service to register new users
     *
     * @var RegistrationService
     */
    protected $service;

    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        parent::setEventDispatcher($dispatcher);
        $dispatcher->addSubscriber($this);
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormFlowEvents::POST_BIND_REQUEST => 'onPostBindRequest',
            FormFlowEvents::POST_VALIDATE => 'onPostValidate',
        );
    }

    public function onPostValidate(PostValidateEvent $event)
    {
        if (1 == $event->getFlow()->getCurrentStepNumber()) {
            // send sms to user
            $key = $this->getService()->makeRequest($event->getFormData()->getPhone(), $this->getToken());
            $this->setKey($key);
        }
    }

    public function onPostBindRequest(PostBindRequestEvent $event)
    {
        if (2 == $event->getFlow()->getCurrentStepNumber()) {
            $token = $event->getFormData()->getToken();
            $code = $event->getFormData()->getSmsCode();
            if (!$this->getService()->isTokenValid($token, $code)) {
                throw new InvalidTokenException("Неверный код " . $code);
            }
        }
    }

    /**
     * @return RegistrationService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    protected function loadStepsConfig()
    {
        return [
            [
                'label' => 'Шаг 1',
                'form_type' => 'BiBundle\Form\Registration',
            ],
            [
                'label' => 'Шаг 2',
                'form_type' => 'BiBundle\Form\Registration',
                'skip' => function ($estimatedCurrentStepNumber, FormFlowInterface $flow) {
                    return $estimatedCurrentStepNumber > 1 && $flow->getFormData()->getSmsCode() !== null;
                },
            ],
            [
                'label' => 'Шаг 3',
                'form_type' => 'BiBundle\Form\Registration'
            ]
        ];
    }

    /**
     * Get registration token
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->getService()->getTokenStorage()->getToken(RegistrationService::TOKEN_REGISTER_SESSION);
    }
}