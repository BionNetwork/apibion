<?php

namespace BiBundle\Service;

use BiBundle\Entity\File;
use BiBundle\Repository\FileRepository;
use Ramsey\Uuid\Uuid;
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

    /**
     * @param UploadedFile $uploadedFile
     * @param string $path
     * @return File
     */
    public function upload(UploadedFile $uploadedFile, $path = '')
    {
        $filename = Uuid::uuid4()->toString() . '.' . $uploadedFile->getClientOriginalExtension();
        $filesystemPath = $this->formatPath($this->uploadDir, $path);
        $uploadedFile->move($filesystemPath, $filename);
        $file = new File();
        $file->setPath($this->formatPath('', $path) . $filename);
        $this->fileRepository->create($file);

        return $file;
    }

    /**
     * @param File $file
     */
    public function create(File $file)
    {
        $this->fileRepository->create($file);
    }

    /**
     * @param $prefix
     * @param string $path
     * @return string
     */
    private function formatPath($prefix, $path = '')
    {
        if ($path) {
            return rtrim($prefix, '/') . '/' . trim($path, '/') . '/';
        } else {
            return rtrim($prefix, '/') . '/';
        }
    }
}