<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Card;
use App\Form\CardType;
use Symfony\Component\HttpFoundation\Request;
class CardController extends AbstractController
{
    #[Route('/cartes', name: 'card_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $cards = $em->getRepository(Card::class)->findAll();
        return $this->render('card/index.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/cartes/ajouter', name: 'card_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
{
    $card = new Card();
    $card->setCreatedAt(new \DateTimeImmutable()); // Initialisation du champ createdAt
    dump($this->getUser());
    $card->setUser($this->getUser());
    $form = $this->createForm(CardType::class, $card);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($card);
        $em->flush();
        return $this->redirectToRoute('card_list');
    }

    return $this->render('card/add.html.twig', [
        'form' => $form->createView(), // Assurez-vous que le formulaire est rendu correctement
    ]);
}

    #[Route('/cartes/editer/{id}', name: 'card_edit')]
    public function editer($id, EntityManagerInterface $em, Request $request): Response
    {
        $card = $em->getRepository(Card::class)->find($id);
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('card_list');
        }

        return $this->render('card/edit.html.twig', [
            'form' => $form,
            'card' => $card
        ]);
    }

    #[Route('/cartes/supprimer/{id}', name: 'card_delete')]
    public function delete($id, EntityManagerInterface $em): Response
    {
        $card = $em->getRepository(Card::class)->find($id);
        $em->remove($card);
        $em->flush();
        return $this->redirectToRoute('card_list');
    }

    #[Route('/cartes/{id}', name: 'card_show')]
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }

}
