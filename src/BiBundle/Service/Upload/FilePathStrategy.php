<?php
/**
 * @package    BiBundle\Service\Upload
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Upload;
use BiBundle\Service\Upload\IdentifiableInterface;

/**
 * Determine path to projects photos
 */
class FilePathStrategy implements FilePathStrategyInterface
{
    /**
     * @var IdentifiableInterface
     */
    protected $entity;

    /**
     * Project's path for photos
     *
     * @return string
     * @throws FilePathStrategyException
     */
    public function getFilePath()
    {
        if (null === $this->getEntity()) {
            throw new FilePathStrategyException("ProjectFilePathStrategy requires entity object, null given");
        }
        $path = sprintf('/:path/%d/', $this->getEntity()->getId());
        return $path;
    }

    /**
     * @return IdentifiableInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param IdentifiableInterface $entity
     */
    public function setEntity(IdentifiableInterface $entity)
    {
        $this->entity = $entity;
    }
}