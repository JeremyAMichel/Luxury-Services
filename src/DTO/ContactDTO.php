<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    public string $firstName = '';

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    public string $lastName = '';

    #[Assert\NotBlank()]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: '/^\+?[0-9\s\-]{7,15}$/',
        message: 'Please enter a valid phone number.'
    )]
    public string $phone = '';


    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 200)]
    public string $message = '';

   
}
