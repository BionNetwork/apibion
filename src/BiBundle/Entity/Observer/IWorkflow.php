<?php
/**
 * @package    BiBundle\Entity\Observer
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Entity\Observer;

interface IWorkflow
{
    /**
     * Identifies entity
     *
     * @return mixed
     */
    public function getEntityIdentifier();

    /**
     * Name of entity
     *
     * @return mixed
     */
    public function getEntityName();
}