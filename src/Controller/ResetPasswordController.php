<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ResetPasswordRequestType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

final class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function request(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            /** @var User */
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->flush();

                $resetPasswordUrl = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('support@luxury-services.com')
                    ->to($user->getEmail())
                    ->subject('Your password reset request')
                    ->html(sprintf('To reset your password, please visit <a href="%s">this link</a>', $resetPasswordUrl));

                $mailer->send($email);

                $this->addFlash('success', 'An email has been sent with a link to reset your password.');
            } else {
                $this->addFlash('danger', 'No account found for this email.');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(Request $request, string $token, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'Invalid token.');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setResetToken(null);
            $entityManager->flush();

            $this->addFlash('success', 'Password successfully updated.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
