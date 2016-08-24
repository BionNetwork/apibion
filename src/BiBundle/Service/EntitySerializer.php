<?php
/**
 * @package    BiBundle\Service
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;


class EntitySerializer
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Serialize entity to array
     *
     * @param $entity
     * @return array
     */
    public function serialize($entity)
    {
        $data = array();

        if ($entity instanceof \DateTimeInterface) {
            return $entity;
        }

        if ($entity instanceof File) {
            return $entity->getRealPath();
        }

        $className = get_class($entity);

        $metaData = $this->em->getClassMetadata($className);

        foreach ($metaData->fieldMappings as $field => $mapping) {
            $method = "get" . ucfirst($field);
            $data[$field] = call_user_func(array($entity, $method));
        }

        foreach ($metaData->associationMappings as $field => $mapping) {
            // Sort of entity object
            $object = $metaData->reflFields[$field]->getValue($entity);

            if (null === $object) continue;
            if ($object instanceof Collection) {
                $data[$field] = $object->toArray();
            } else {
                $uow = $this->em->getUnitOfWork();
                $data[$field] = $uow->getEntityIdentifier($object);
            }
        }

        return $data;
    }

    /**
     * Serialize entity to array keeping database field names
     *
     * @param $entity
     * @return array
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function serializeAsArray($entity)
    {
        $className = get_class($entity);

        $uow = $this->em->getUnitOfWork();
        $entityPersister = $uow->getEntityPersister($className);
        $classMetadata = $entityPersister->getClassMetadata();

        $result = array();
        foreach ($uow->getOriginalEntityData($entity) as $field => $value) {
            if (isset($classMetadata->associationMappings[$field])) {
                $assoc = $classMetadata->associationMappings[$field];

                // Only owning side of x-1 associations can have a FK column.
                if (!$assoc['isOwningSide'] || !($assoc['type'] & \Doctrine\ORM\Mapping\ClassMetadata::TO_ONE)) {
                    continue;
                }

                $newValId = [];
                if ($value !== null) {
                    $newValId = $uow->getEntityIdentifier($value);
                }

                $targetClass = $this->em->getClassMetadata($assoc['targetEntity']);
//                $owningTable = $entityPersister->getOwningTable($field);

                foreach ($assoc['joinColumns'] as $joinColumn) {
                    $sourceColumn = $joinColumn['name'];
                    $targetColumn = $joinColumn['referencedColumnName'];

                    if ($value === null) {
                        $result[$sourceColumn] = null;
                    } else if ($targetClass->containsForeignIdentifier) {
                        $result[$sourceColumn] = $newValId[$targetClass->getFieldForColumn($targetColumn)];
                    } else {
                        $result[$sourceColumn] = $newValId[$targetClass->fieldNames[$targetColumn]];
                    }
                }
            } elseif (isset($classMetadata->columnNames[$field])) {
                $columnName = $classMetadata->columnNames[$field];
                $result[$columnName] = $value;
            }
        }

        return array($className, $result);
    }

    /**
     * Deserialize array of data to entity
     *
     * @param array $data
     * @return object
     */
    public function deserialize(array $data)
    {
        list($class, $result) = $data;

        $uow = $this->em->getUnitOfWork();
        return $uow->createEntity($class, $result);
    }
}