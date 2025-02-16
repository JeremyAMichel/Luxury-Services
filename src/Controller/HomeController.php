<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(JobOfferRepository $jobOfferRepository, CategoryRepository $categoryRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'latestJobOffers' => $jobOfferRepository->findLatestActiveOffers(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
