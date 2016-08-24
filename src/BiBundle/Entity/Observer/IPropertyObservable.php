<?php
/**
 * @package    BiBundle\Entity\Observer
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Entity\Observer;

interface IPropertyObservable
{
    /**
     * List of properties that are visible during modifications
     *
     * @return mixed
     */
    public function propertiesVisibleInChangeSet();

    /**
     * List of properties that are not visible during modifications
     *
     * @return mixed
     */
    public function propertiesNotVisibleInChangeSet();
}