<?php
/**
 * @package    BiBundle\Service
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use BiBundle\Entity\Image;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use BiBundle\Entity\ImageSize;

/**
 * Image service
 */
class Images extends UserAwareService
{
    public function save(Image $image)
    {
        $em = $this->getEntityManager();
        if (null === $image->getId()) {
            $image->setCreatedBy($this->getUser());
        }
        $em->persist($image);
        $em->flush();
    }

    /**
     * Creates image from uploaded file
     *
     * @param UploadedFile $file
     * @return Image
     */
    public function createImageFromFile(UploadedFile $file)
    {
        $image = new Image();
        $image->setName($file->getClientOriginalName());
        $image->setSize($file->getClientSize());
        $image->setMime($file->getClientMimeType());
        $image->setPublished(true);
        $image->setQueued(false);
        return $image;
    }

    /**
     * Creates image size from uploaded file
     *
     * @param UploadedFile $file
     * @param array $options
     * @return ImageSize
     */
    public function createImageSizeFromFile(UploadedFile $file, array $options)
    {
        $image = new ImageSize($options);

        if (empty($options['name'])) {
            $image->setName($file->getClientOriginalName());
        }
        return $image;
    }

    /**
     * Return image name based on its sizes
     *
     * @param $path
     * @param ImageSize $image
     * @return string
     */
    public function getImageSizeName($path, ImageSize $image)
    {
        $fileName = pathinfo($path, PATHINFO_FILENAME);

        $size = sprintf("%sx%s", $image->getWidth(), $image->getHeight());
        $name = $fileName . '_' . $size;

        return $name;
    }
}