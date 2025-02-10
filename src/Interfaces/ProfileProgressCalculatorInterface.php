<?php

namespace App\Interfaces;

use App\Entity\Candidate;

interface ProfileProgressCalculatorInterface
{
    public function calculerProgress(Candidate $candidate): int;
}