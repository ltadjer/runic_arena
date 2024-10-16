<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CardController extends AbstractController
{
    #[Route('/card', name: 'app_card')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }

    #[Route('/cartes/ajouter', name: 'app_card_add')]
    public function new(): Response
    {
        return $this->render('card/add.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }
}
