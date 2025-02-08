<?php

namespace App\Traits;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait SluggerTrait
{
    private function autoSlug(FormEvent $event): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $titleOrName = $data['title'] ?? $data['name'] ?? '';
            $data['slug'] = strtolower($slugger->slug($titleOrName));
            $event->setData($data);
        }
    }
}