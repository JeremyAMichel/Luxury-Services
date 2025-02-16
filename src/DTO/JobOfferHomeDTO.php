<?php

namespace App\DTO;

use DateTimeImmutable;

class JobOfferHomeDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?int $salary,
        public readonly ?DateTimeImmutable $closingAt,
        public readonly ?string $jobLocation,
        public readonly string $reference,
        public readonly string $description,
        public readonly string $slug,
        public readonly string $category
    ) {
    }
}