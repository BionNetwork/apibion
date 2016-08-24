<?php

namespace ApiBundle\Service\Normalizer;

use FOS\RestBundle\Normalizer\Exception\NormalizationException;
use FOS\RestBundle\Normalizer\ArrayNormalizerInterface;

class UnderscoreNormalizer implements ArrayNormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function normalize(array $data)
    {
        $this->normalizeArray($data);

        return $data;
    }

    /**
     * Normalizes an array.
     *
     * @param array &$data
     *
     * @throws NormalizationException
     */
    private function normalizeArray(array &$data)
    {
        foreach ($data as $key => $val) {
            $normalizedKey = $this->normalizeString($key);
            if ($normalizedKey != $key) {
                if (array_key_exists($normalizedKey, $data)) {
                    throw new NormalizationException(sprintf(
                        'The key "%s" is invalid as it will override the existing key "%s"', $key, $normalizedKey
                    ));
                }
                unset($data[$key]);
                $data[$normalizedKey] = $val;
                $key = $normalizedKey;
            }
            if (is_array($val)) {
                $this->normalizeArray($data[$key]);
            }
        }
    }

    /**
     * Normalizes a string.
     *
     * @param string $string
     *
     * @return string
     */
    protected function normalizeString($string)
    {
        $string = preg_replace_callback('/([A-Z])/', function ($matches) {
            return '_' . strtolower($matches[1]);
        }, $string);
        return $string;
    }
}
