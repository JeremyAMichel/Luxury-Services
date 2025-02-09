<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {
        // TODO : if user is not verified, display a proper template
        /** @var User */
        $user = $this->getUser();

        if ($user->isVerified() === false) {
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
            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilPictureFile')->getData();
            $passportFile = $form->get('passportFile')->getData();

            // this condition is needed because the 'profilePicture' field is not required
            // so the file must be processed only when a file is uploaded
            if ($profilePictureFile) {
                $profilePictureName = $fileUploader->upload($profilePictureFile, $candidate, 'profilPicture', 'profile-pictures');
                $candidate->setProfilPicture($profilePictureName);
            }

            // this condition is needed because the 'passportFile' field is not required
            // so the file must be processed only when a file is uploaded
            if ($passportFile) {
                $passportName = $fileUploader->upload($passportFile, $candidate, 'passport', 'passport');
                $candidate->setPassport($passportName);
            }

            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully!');

            return $this->redirectToRoute('app_profile');
        }

        if ($candidate->getProfilPicture()) {
            $originalProfilePictureFilename = preg_replace('/-\w{13}(?=\.\w{3,4}$)/', '', $candidate->getProfilPicture());
        }

        if ($candidate->getPassport()) {
            $originalPassportFilename = preg_replace('/-\w{13}(?=\.\w{3,4}$)/', '', $candidate->getPassport());
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'candidate' => $candidate,
            'originalProfilPicture' => $originalProfilePictureFilename ?? null,
            'originalPassport' => $originalPassportFilename ?? null,
        ]);
    }
}
