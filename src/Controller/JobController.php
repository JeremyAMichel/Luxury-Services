<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\JobOfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JobController extends AbstractController
{
    #[Route('/job', name: 'app_job')]
    public function index(
        JobOfferRepository $jobOfferRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $categorySlug = $request->query->get('category', 'all');

        $queryBuilder = $jobOfferRepository->createQueryBuilder('j')
            ->orderBy('j.createdAt', 'DESC');

        if ($categorySlug !== 'all') {
            $queryBuilder
                ->join('j.category', 'c')
                ->andWhere('LOWER(c.name) = :category')
                ->setParameter('category', strtolower($categorySlug));
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('job/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'jobOffers' => $pagination,
            'currentCategory' => $categorySlug
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
