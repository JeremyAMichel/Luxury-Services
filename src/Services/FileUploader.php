<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {}

    public function upload(UploadedFile $file, object $entity, string $fieldName, string $folder): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory() . '/' . $folder, $fileName);
        } catch (FileException $e) {
            throw new \Exception('There was a problem uploading your file. Please try again.');
        }

        /// delete the old file
        $this->removeOldFile($entity, $fieldName);

        return $fileName;
        
    }

    private function removeOldFile(object $entity, string $fieldName): void
    {
        $getter = 'get' . ucfirst($fieldName);
        $oldFile = $entity->$getter();

        if ($oldFile) {
            $oldFilePath = $this->getTargetDirectory() . '/' . $oldFile;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
