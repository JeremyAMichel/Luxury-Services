<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private RequestStack $requestStack;
    private RouterInterface $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (in_array('ROLE_DELETED', $user->getRoles())) {
            /** @var Session */
            $session = $this->requestStack->getSession();

            $event->getRequest()->getSession()->invalidate(); // Déconnecte l'utilisateur
            $event->getRequest()->getSession()->set('_security_main', null); // Efface la session
            
            $session->getFlashBag()->add('danger', 'Votre compte a été supprimé.');

            $response = new RedirectResponse($this->router->generate('app_home'));
            

            $response->send();
            exit;
        }
    }
}
