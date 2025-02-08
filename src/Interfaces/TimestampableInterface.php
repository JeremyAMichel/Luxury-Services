<?php

namespace App\Interfaces;

interface TimestampableInterface
{
    public function getId(): ?int;
    public function setCreatedAt(\DateTimeImmutable $createdAt): static;
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static;
}