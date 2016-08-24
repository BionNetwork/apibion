<?php
/**
 * @package    BiBundle\Service\Upload
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Upload;

interface FilePathStrategyInterface
{
    /**
     * Gets file path
     *
     * @return string
     */
    public function getFilePath();
}