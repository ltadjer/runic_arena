<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Card;
use Symfony\Bundle\SecurityBundle\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em, Security $security): Response
    {
        $cards = [];
        
        $cards = $em->getRepository(Card::class)->findBy([], ['id' => 'DESC'], 5);

        return $this->render('home/index.html.twig', [
            'cards' => $cards,
        ]);
    }
}
