<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\User;
use BiBundle\Entity\Purchase;
use BiBundle\Entity\Activation;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class ActivationTransferObject
{

    /**
     * @var HostBasedUrl
     */
    private $url;

    /**
     * @var User
     */
    private $user;

    public function __construct(UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->user = $userAwareService->getUser();
        $this->url = $url;
    }

    /**
     * Get user's activated cards list
     *
     * @param \BiBundle\Entity\Activation[] $data
     * @return array
     */
    public function getListData(array $data)
    {
        $result = [];

        foreach ($data as $activation) {
            $item = [
                'id' => $activation->getId(),
                'created_on' => !empty($activation->getCreatedOn()) ? $activation->getCreatedOn()->getTimestamp() : null,
                'card' => $this->getCard($activation->getCard()),
            ];
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param Card|null $card
     * @return array|null
     */
    protected function getCard(\BiBundle\Entity\Card $card = null)
    {
        $result = null;
        if ($card) {
            $result = [
                'id' => $card->getId(),
                'name' => $card->getName(),
                'type' => $card->getType(),
            ];
        }
        return $result;
    }
}