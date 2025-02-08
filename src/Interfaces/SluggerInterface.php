<?php

namespace App\Interfaces;

interface SluggerInterface
{
    public function getSlug(): ?string;
    public function setSlug(string $slug): static;
}