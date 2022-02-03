<?php

namespace App\Controller\Back;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * Display login form and process login form (GET + POST)
     * 
     * @Route("/", name="login_index")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Les deux lignes suivantes servent en cas d'échec à la connexion
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('back/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * Logout
     * 
     * @Route("/logout", name="login_logout")
     */
    public function logout()
    {
        // Ce code ne sera jamais exécuté
        // le composant de sécurité va intercepter la requête avant.
    }
}