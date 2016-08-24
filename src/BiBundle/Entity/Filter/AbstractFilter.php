<?php

namespace BiBundle\Entity\Filter;

use FOS\RestBundle\Normalizer\CamelKeysNormalizer;

abstract class AbstractFilter
{
    const LIMIT_MAX = 200;
    const OFFSET_MAX = 1000000;

    private $limit;
    private $offset;

    public function __construct($initialArray = [])
    {
        if (!empty($initialArray)) {
            $normalizer = new CamelKeysNormalizer();
            $initialArrayNormalized = $normalizer->normalize($initialArray);
            foreach ($initialArrayNormalized as $key => $value) {
                if (array_key_exists($key, get_object_vars($this))) {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Возвращает максимальное количество строк в запросе
     *
     * @return int
     */
    public function getLimit()
    {
        if ($this->limit >= self::LIMIT_MAX) {
            return self::LIMIT_MAX;
        }
        return $this->limit;
    }

    /**
     * Возвращает смещение
     * 
     * @return int
     */
    public function getOffset()
    {
        if ($this->offset >= self::OFFSET_MAX) {
            return self::OFFSET_MAX;
        }
        return $this->offset;
    }
}
