<?php

namespace App\Interfaces;

use App\Entity\Candidate;

interface FileHandlerInterface
{
    public function handleFiles(Candidate $candidate, array $files): void;
}