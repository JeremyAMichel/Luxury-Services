<?php

namespace App\Interfaces;

use App\Entity\User;

interface PasswordUpdaterInterface
{
    public function updatePassword(User $user, string $email, string $newPassword): void;
}