<?php

namespace App\Traits;

use App\Interfaces\TimestampableInterface;
use Symfony\Component\Form\FormEvent;

trait TimestampableTrait
{
    private function attachTimestamps(FormEvent $event): void
    {
        $entity = $event->getData();
        
        if (!$entity instanceof TimestampableInterface) {
            return;
        }

        

        $entity->setUpdatedAt(new \DateTimeImmutable());

        if (!$entity->getId()) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

    }
}