<?php

namespace ApiBundle\Service\DataTransferObject\Object;


abstract class BaseValueObject implements \JsonSerializable
{
    /**
     * Создание и заполнение объекта из массива
     * @param null $data
     */
    public function __construct($data = null)
    {
        if ($data) {
            foreach (array_keys(get_object_vars($this)) as $fieldName) {
                if (array_key_exists($fieldName, $data)) {
                    $this->$fieldName = $data[$fieldName];
                }
            }
        }
    }

    /**
     * Создание и заполнение объекта из массива
     *
     * @param array $data
     *
     * @return mixed
     */
    static public function fromArray(array $data)
    {
        $myClass = get_called_class();
        $object = new  $myClass();
        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }
}