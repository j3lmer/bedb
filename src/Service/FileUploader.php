<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;

class FileUploader
{
    private string $uploadPath;
    private $slugger;
    private $urlHelper;
    private Filesystem $filesystem;

    public function __construct(string $publicPath,  SluggerInterface $slugger, UrlHelper $urlHelper, Filesystem $filesystem)
    {
        $this->publicPath = $publicPath . "/uploads";
        $this->slugger = $slugger;
        $this->urlHelper = $urlHelper;
        $this->filesystem = $filesystem;

    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getUploadPath(), $fileName);
        } catch (FileException $e) {
            $this->appendToLogfile("Exception occured: " . $e->getMessage());
        }

        return $fileName;
    }

    public function getUploadPath(): string
    {
        return $this->publicPath;
    }

    protected function appendToLogfile(string $text): void
    {
        $this->filesystem->appendToFile("src/Service/FileLog.txt", $text);
    }
}