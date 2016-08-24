<?php
/**
 * Created by PhpStorm.
 * User: storageprocedure
 * Date: 11.07.2016
 * Time: 12:43
 */

namespace BiBundle\Service\Upload;

/**
 * Identifiable objects that have their own upload paths
 */
interface IdentifiableInterface
{
    public function getId();
}