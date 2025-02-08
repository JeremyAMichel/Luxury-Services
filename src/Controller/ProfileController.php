<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // TODO : if user is not verified, display a proper template
        /** @var User */
        $user = $this->getUser();

        if($user->isVerified() === false) {
            return $this->render('errors/not-verified.html.twig');
        }

        $candidate = $user->getCandidate();

        if ($candidate === null) {
            $candidate = new Candidate();
            $candidate->setUser($user);
        }

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($candidate);
            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        
        ]);
    }
}
