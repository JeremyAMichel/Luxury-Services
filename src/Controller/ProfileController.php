<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Interfaces\FileHandlerInterface;
use App\Interfaces\PasswordUpdaterInterface;
use App\Interfaces\ProfileProgressCalculatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        FileHandlerInterface $fileHandler,
        PasswordUpdaterInterface $passwordUpdater,
        ProfileProgressCalculatorInterface $progressCalculator
    ): Response {
      
        /** @var User */
        $user = $this->getUser();

        if ($user->isVerified() === false) {
            return $this->render('errors/not-verified.html.twig');
        }

        $candidate = $user->getCandidate();

        // If the user does not have a candidate profile, create a new one
        $candidate = $user->getCandidate() ?? new Candidate();
        $candidate->setUser($user);

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = [
                'profilPicture' => $form->get('profilPictureFile')->getData(),
                'passport' => $form->get('passportFile')->getData(),
                'cv' => $form->get('cvFile')->getData(),
            ];
            $fileHandler->handleFiles($candidate, $files);

            $email = $form->get('email')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if ($email && $newPassword) {
                $passwordUpdater->updatePassword($user, $email, $newPassword);
            } elseif ($email || $newPassword) {
                $this->addFlash('danger', 'Email and password must be filled together to change password.');
            }

            $progressCalculator->calculerProgress($candidate);

            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully!');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'candidate' => $candidate,
            'originalProfilPicture' => $this->getOriginalFilename($candidate->getProfilPicture()),
            'originalPassport' => $this->getOriginalFilename($candidate->getPassport()),
            'originalCv' => $this->getOriginalFilename($candidate->getCv()),
        ]);
    }

    #[Route('/profile/delete/{id}', name: 'app_profile_delete')]
    public function delete(
        Candidate $candidate,
        EntityManagerInterface $entityManager,
    ): Response {
        // verifie si la personne qui supprime est celle qui est connecté
        /** @var User */
        $user = $this->getUser();
        if ($user->getCandidate() !== $candidate) {
            $this->addFlash('danger', 'You are not allowed to delete this profile!, the admin will be informed of this action.');

            return $this->redirectToRoute('app_profile');
        }

        $candidate->setDeletedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_DELETED']);
        $entityManager->flush();

        // $this->addFlash('success', 'Profile deleted successfully!');

        return $this->redirectToRoute('app_logout');
    }

    private function getOriginalFilename(?string $filename): ?string
    {
        return $filename ? preg_replace('/-\w{13}(?=\.\w{3,4}$)/', '', $filename) : null;
    }
}
