<?php

namespace App\Services;

use App\Entity\Candidate;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHandler
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    
    public function handleFiles(Candidate $candidate, array $files): void
    {
        foreach ($files as $field => $file) {
            if ($file instanceof UploadedFile) {
                $methodName = 'set' . ucfirst($field);
                $fileName = $this->fileUploader->upload($file, $candidate, $field, $this->getUploadDirectory($field));
                if (method_exists($candidate, $methodName)) {
                    $candidate->$methodName($fileName);
                }
            }
        }
    }

    private function getUploadDirectory(string $field): string
    {
        return match ($field) {
            'profilPicture' => 'profile-pictures',
            'passport' => 'passport',
            'cv' => 'curriculum-vitae',
            default => 'uploads',
        };
    }
}