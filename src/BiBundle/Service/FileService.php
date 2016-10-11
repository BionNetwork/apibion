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

    public function upload(UploadedFile $uploadedFile, $path = '')
    {
        $filename = Uuid::uuid4()->toString() . '.' . $uploadedFile->getClientOriginalExtension();
        $filesystemPath = $this->formatPath($this->uploadDir, $filename, $path);
        $uploadedFile->move($filesystemPath, $filename);
        $file = new File();
        $file->setPath($this->formatPath('', $filename, $path));
        $this->fileRepository->create($file);

        return $file;
    }

    public function create(File $file)
    {
        $this->fileRepository->create($file);
    }

    private function formatPath($prefix, $filename, $path = '')
    {
        if ($path) {
            return rtrim($prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        } else {
            return rtrim($prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        }
    }
}