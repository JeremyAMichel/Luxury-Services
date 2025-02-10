<?php
namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ProfileField
{
    public function __construct(
        public ?int $weight = 1 // Vous pouvez définir un poids par défaut
    ) {}
}
