<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

class PasswordUpdater
{
    private UserPasswordHasherInterface $passwordHasher;
    private MailerInterface $mailer;
    private RequestStack $requestStack;

    public function __construct(UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, RequestStack $requestStack)
    {
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
    }

    /**
     * Updates the user's password and sends a confirmation email.
     *
     * @param User   $user        The user entity
     * @param string $email       The user's email
     * @param string $newPassword The new password
     */
    public function updatePassword(User $user, string $email, string $newPassword): void
    {
        /** @var Session $session */
        $session = $this->requestStack->getSession();

        /** @var FlashBag $flashBag */
        $flashBag = $session->getFlashBag();

        if ($user->getEmail() !== $email) {
            $flashBag->add('danger', 'The email you entered does not match the email associated with your account.');
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);

        try {
            $mail = (new TemplatedEmail())
                ->from('support@luxury-services.com')
                ->to($user->getEmail())
                ->subject('Change of password')
                ->htmlTemplate('emails/change-password.html.twig');

            $this->mailer->send($mail);
            $flashBag->add('success', 'Your password has been changed successfully!');
        } catch (\Exception $e) {
            $flashBag->add('danger', 'An error occurred while sending the message: ' . $e->getMessage());
        }
    }
}