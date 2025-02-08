<?php

namespace App\Controller;

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

        return $this->render('profile/index.html.twig', [
            
        
        ]);
    }
}
