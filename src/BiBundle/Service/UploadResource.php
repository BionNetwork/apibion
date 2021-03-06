<?php

namespace BiBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use BiBundle\Service\Exception\UploadFileException;

class UploadResource implements IUpload
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Path to uploaded file
     * @var string
     */
    protected $uploadPath;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Корневой путь к директории для загрузки
     * @return string
     */
    public function getUploadRootPath()
    {
        return $this->getParameter('upload_dir');
    }

    /**
     * Upload files
     *
     * @param UploadedFile $file
     * @param array $options
     * @return array list of uploaded files
     * @throws UploadFileException
     */
    public function upload(UploadedFile $file, $options = array())
    {
        $originalExtension = $file->getClientOriginalExtension();
        if($originalExtension && !in_array($originalExtension, ['bin', 'php', 'sh'])) {
            $name = $file->getClientOriginalName();
        } else {
            $name = md5(uniqid()) . '.tmp';
        }
        $uploadPath = $this->getUploadPath();
        $directory = $this->getUploadRootPath() . sprintf('/%s/', trim($uploadPath, '/'));
        $file->move($directory, $name);

        $path = $uploadPath . DIRECTORY_SEPARATOR . $name;

        return [
            'path' => $path,
            'full_path' => $directory . DIRECTORY_SEPARATOR . $name
        ];
    }

    /**
     * @param $param
     * @return mixed
     */
    protected function getParameter($param)
    {
        return $this->getContainer()->getParameter($param);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     * @throws Exception\UploadFileException
     */
    public function getUploadPath()
    {
        if (null === $this->uploadPath) {
            throw new Exception\UploadFileException("Photo upload path is not set");
        }
        return $this->uploadPath;
    }

    /**
     * @param string $uploadPath
     */
    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }
}