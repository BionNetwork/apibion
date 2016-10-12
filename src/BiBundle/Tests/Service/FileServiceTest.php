<?php

namespace BiBundle\Tests\Service;

use BiBundle\Entity\File;
use BiBundle\Repository\FileRepository;
use BiBundle\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileServiceTest extends KernelTestCase
{
    private $uploadDir;

    /** @var  FileService */
    private $service;

    /** @var  FileRepository */
    private $repository;

    protected function setUp()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $this->service = $container->get('bi.file.service');
        $this->repository = $container->get('repository.file');
        $this->uploadDir = $container->getParameter('upload_dir');
    }

    public function testFileServiceUpload()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $uploadedFile->method('getClientOriginalExtension')->willReturn('txt');
        $uploadedFile->expects($this->once())->method('move')->with($this->anything(), $this->anything());
        $path = 'cards/etc';
        $file = $this->service->upload($uploadedFile, $path);

        $this->assertInstanceOf(File::class, $file);
        $this->assertStringStartsWith('/uploads/' . $path, $file->getPath());
        $this->assertStringEndsWith('.txt', $file->getPath());
        $this->assertStringEndsNotWith('..txt', $file->getPath());
    }
}
