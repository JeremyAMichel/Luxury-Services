<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JobController extends AbstractController
{
    #[Route('/job', name: 'app_job')]
    public function index(): Response
    {
        return $this->render('job/index.html.twig', [
         
        ]);
    }

    #[Route('/job/{slug}', name: 'app_job_show')]
    public function show(string $slug, JobOfferRepository $jobOfferRepository): Response
    {
        return $this->render('job/show.html.twig', [
            'jobOffer' => $jobOfferRepository->findOneBy(['slug' => $slug]),
        ]);
    }
}
