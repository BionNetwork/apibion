<?php

namespace BiBundle\Service;

use BiBundle\BiBundle;
use BiBundle\Service\Backend\Exception;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ActivationService extends UserAwareService
{

    /**
     * Получение активаций карточек пользователя
     *
     * @param User $user
     */
    public function getUserActivations(\BiBundle\Entity\User $user)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Activation')->getUserActivations($user);
    }

    /**
     * Возвращает активации по фильтру
     *
     * @param \BiBundle\Entity\Filter\Activation $filter
     *
     * @return \BiBundle\Entity\Activation[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Activation $filter)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('BiBundle:Activation')->getByFilter($filter);

        $resultArray = [];
        foreach ($items as $item) {
            $resultArray[] = $item;
        }
        
        return $resultArray;
    }

    /**
     * Построение тела запроса для фейковой загрузки данных
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return string
     */
    public function mockQueryBuilder(\BiBundle\Entity\Activation $activation)
    {
        // Говнокод для временных нужд и очень хрупкая конструкция
        // Можно исполльзовать для построения полноценного фильтра
        //
        //
        try {
            $em = $this->getEntityManager();

            $query = [
                'X' => [
                    // Ось периода
                    'field_name' => null,
                    'period' => ['2012-12-01', '2017-12-20'],
                    'interval' => 'month'
                ],
                'Y' => [
                    // Использовать формулу тут
                    'field_name' => null,
                ],
                'Organizations' => [
                    'field_name' => null,
                    'values' => []
                ],
                'filters' => [
                    //['field_name' => null, 'values': []],
                    //['field_name' => null, 'values': []],
                ]
            ];


            $argumentList = $activation->getCard()->getArgument();
            if (!json_decode($activation->getLoadDataRespond(), JSON_UNESCAPED_UNICODE)) {
                throw new Exception('Метадата загруженных данных отсутствует или невалидна');
            }
            $loadDataRespond = json_decode($activation->getLoadDataRespond(), JSON_UNESCAPED_UNICODE);

            $argumentBonds = $em->getRepository('BiBundle:ArgumentBond')->findBy(['activation' => $activation]);

            $neededKeys = [
                'X' => null,
                'Y' => null,
            ];

            foreach ($argumentBonds as $bond) {
                if ($bond->getArgument()->getDimension() === 'X') {
                    $keyArray = [
                        $bond->getResource()->getId(),
                        $bond->getTableName(),
                        $bond->getColumnName()
                    ];
                    $neededKeys['X'] = implode(';', $keyArray);
                }
                // Не повторение кода, а предусмотрена отдельная обработка под случай с формулой
                if ($bond->getArgument()->getDimension() === 'Y') {
                    $keyArray = [
                        $bond->getResource()->getId(),
                        $bond->getTableName(),
                        $bond->getColumnName()
                    ];
                    $neededKeys['Y'] = implode(';', $keyArray);
                }
            }

            if ($neededKeys['X'] === null || $neededKeys['Y'] === null) {
                throw new Exception('Комплект обязательных аргументов неполный');
            }

            $hashDictionary = [];
            $organizationField = null;
            foreach ($loadDataRespond['sources'] as $remoteResourceId => $source) {
                // Вызнаем локальный ID источника
                $resource = $em->getRepository('BiBundle:Resource')->findOneBy([
                    'activation' => $activation,
                    'remoteId' => $remoteResourceId
                ]);
                $sourcedata = array_shift($source);
                // Вызнаем имя таблицы
                $table_name = $sourcedata['orig_table'];
                // Вызнаем оригинальные имена столбцов и добавляем хеш в словарь
                foreach ($sourcedata['columns'] as $column) {
                    // Строим ключ словаря
                    $keyArray = [
                        $resource->getId(),
                        $table_name,
                        $column['orig_column']
                    ];
                    if (mb_strrpos(mb_strtolower($column['orig_column']), 'организация') !== false) {
                        $organizationField = $column['click_column'];
                    }
                    $hashDictionary[implode(';', $keyArray)] = $column['click_column'];

                }
            }
            if (array_key_exists($neededKeys['X'], $hashDictionary)) {
                $query['X']['field_name'] = $hashDictionary[$neededKeys['X']];
            }

            if (array_key_exists($neededKeys['Y'], $hashDictionary)) {
                $query['Y']['field_name'] = 'sum(' . $hashDictionary[$neededKeys['Y']] . ')';
            }

            if ($organizationField) {
                $query['Organizations']['field_name'] = $organizationField;
            } else {
                throw new Exception('Не удалось определить колонку с организациями');
            }

            return json_encode($query, JSON_UNESCAPED_UNICODE);
        } catch (Exception $ex) {
            return null;
        }
    }

}
