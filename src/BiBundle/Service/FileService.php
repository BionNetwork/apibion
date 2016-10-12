<?php

namespace BiBundle\Service;

use BiBundle\Entity\File;
use BiBundle\Repository\FileRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    /** @var  FileRepository */
    private $fileRepository;

    /** @var  string */
    private $uploadPath;

    /** @var  string */
    private $webRootDir;

    /** @var  string */
    private $webUploadDir;

    /**
     * FileUploadService constructor.
     * @param FileRepository $fileRepository
     * @param $webRootDir
     * @param $webUploadDir
     */
    public function __construct(FileRepository $fileRepository, $webRootDir, $webUploadDir)
    {
        $this->fileRepository = $fileRepository;
        $this->webRootDir = $webRootDir;
        $this->webUploadDir = $webUploadDir;
        $this->uploadPath = $webRootDir . $webUploadDir;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $path
     * @return File
     */
    public function upload(UploadedFile $uploadedFile, $path = '')
    {
        $filename = Uuid::uuid4()->toString() . '.' . $uploadedFile->getClientOriginalExtension();
        $filesystemPath = $this->formatPath($this->uploadPath, $path);
        $uploadedFile->move($filesystemPath, $filename);
        $file = new File();
        $file->setPath($this->formatPath($this->webUploadDir, $path) . $filename);
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
     * @param File $file
     */
    public function delete(File $file)
    {
        $filesystem = new Filesystem();
        $filePath = $this->webRootDir . $file->getPath();
        if ($filesystem->exists($filePath)) {
            $filesystem->remove($filePath);
        }
        $this->fileRepository->delete($file);
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