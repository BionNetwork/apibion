<?php

namespace BiBundle\Service;

use BiBundle\Entity\CardCategory;
use BiBundle\Repository\CardCategoryRepository;

class CardCategoryService
{
    /**
     * @var CardCategoryRepository
     */
    private $repository;

    /**
     * CardCategoryService constructor.
     * @param CardCategoryRepository $repository
     */
    public function __construct(CardCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all card categories
     *
     * @param \BiBundle\Entity\Filter\CardCategory $filter
     *
     * @return CardCategory[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\CardCategory $filter)
    {
        return $this->repository->findByFilter($filter);
    }
}
