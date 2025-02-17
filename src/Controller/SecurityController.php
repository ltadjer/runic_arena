<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response {
		if ($this->getUser()) {
            return $this->redirectToRoute('card_list');
        }
    	$error = $authenticationUtils->getLastAuthenticationError();

    	$lastUsername = $authenticationUtils->getLastUsername();

    	return $this->render('security/login.html.twig', [
    		'last_username' => $lastUsername,
    		'error'         => $error,
    	]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): never {}


}
