<?php

namespace ApiBundle\Service\DataTransferObject;

use BiBundle\Entity\User;
use BiBundle\Repository\ActivationRepository;
use BiBundle\Service\UserAwareService;
use BiBundle\Service\Utils\HostBasedUrl;

class ActivationTransferObject
{

    /**
     * @var ActivationRepository
     */
    private $repository;

    /**
     * @var HostBasedUrl
     */
    private $url;

    /**
     * @var User
     */
    private $user;

    public function __construct(ActivationRepository $repository, UserAwareService $userAwareService, HostBasedUrl $url)
    {
        $this->repository = $repository;
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
                'activation_status' => $activation->getActivationStatus()->getCode(),
                'card' => $this->getCard($activation->getCard())
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
                'author' => $card->getAuthor(),
                'description' => $card->getDescription(),
                'description_long' => $card->getDescriptionLong(),
                'rating' => $card->getRating(),
                // @todo set images
                'carousel' =>  [],
                'created_on' => !empty($card->getCreatedOn()) ? $card->getCreatedOn()->format('Y/m/d H:i') : null,
                'updated_on' => !empty($card->getUpdatedOn()) ? $card->getUpdatedOn()->format('Y/m/d H:i') : null,
            ];
        }
        return $result;
    }

    /**
     * @return ActivationRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}