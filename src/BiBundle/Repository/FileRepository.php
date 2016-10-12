<?php

namespace BiBundle\Repository;

use BiBundle\Entity\File;
use Doctrine\ORM\EntityRepository;

/**
 * FileRepository
 */
class FileRepository extends EntityRepository
{
    /**
     * @param File $file
     */
    public function create(File $file)
    {
        $this->getEntityManager()->persist($file);
        $this->getEntityManager()->flush($file);
    }

    /**
     * @param File $file
     */
    public function delete(File $file)
    {
        $this->getEntityManager()->remove($file);
        $this->getEntityManager()->flush($file);
    }
}
