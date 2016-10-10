<?php

namespace BiBundle\Service;

use BiBundle\Entity\File;
use BiBundle\Repository\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    /** @var  FileRepository */
    private $fileRepository;

    /** @var  string */
    private $uploadDir;

    /**
     * FileUploadService constructor.
     * @param FileRepository $fileRepository
     * @param string $uploadDir
     */
    public function __construct(FileRepository $fileRepository, $uploadDir)
    {
        $this->fileRepository = $fileRepository;
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $uploadedFile, $path = '')
    {
        $name = md5(uniqid()) . $uploadedFile->getClientOriginalExtension();
        $path = $this->getUploadPath($name, $path);
        $uploadedFile->move($path, $name);
        $file = new File();
        $file->setPath($path);
        $this->fileRepository->create($file);

        return $file;
    }

    private function getUploadPath($filename, $path = '')
    {
        if ($path) {
            return rtrim($this->uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        } else {
            return rtrim($this->uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        }
    }
}