<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Card;
use App\Form\CardType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\CardNameGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class CardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function adminDashboard(EntityManagerInterface $em, ChartBuilderInterface $chartBuilder): Response
    {
        $cards = $em->getRepository(Card::class)->findAll();

        // Nombre de cartes par type
        $cardsByType = $em->getRepository(Card::class)->countByType();

        // Nombre de cartes par classe
        $cardsByClass = $em->getRepository(Card::class)->countByClass();

        // Créer le graphique pour les cartes par type
        $cardsByTypeChart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $cardsByTypeChart->setData([
            'labels' => array_column($cardsByType, 'type'),
            'datasets' => [
                [
                    'data' => array_column($cardsByType, 'count'),
                    'backgroundColor' => ['#76290B', '#C2A896'],
                ],
            ],
        ]);
        // Créer le graphique pour les cartes par classe
        $cardsByClassChart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $cardsByClassChart->setData([
            'labels' => array_column($cardsByClass, 'class'),
            'datasets' => [
                [
                    'data' => array_column($cardsByClass, 'count'),
                    'backgroundColor' => ['#76290B', '#C2A896', '#3B1505', '#FFF3EA'],
                ],
            ],
        ]);

        return $this->render('card/dashboard.html.twig', [
            'cards' => $cards,
            'cardsByTypeChart' => $cardsByTypeChart,
            'cardsByClassChart' => $cardsByClassChart,
        ]);
    }

    #[Route('/cartes', name: 'card_list')]
    public function list(EntityManagerInterface $em, Security $security): Response
    {
        $cards = [];
        if ($security->isGranted('ROLE_ADMIN')) {
            $cards = $em->getRepository(Card::class)->findBy([], ['createdAt' => 'DESC']);
        } else {
            $cards = $em->getRepository(Card::class)->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);
        }
        return $this->render('card/index.html.twig', [
            'cards' => $cards,
        ]); 
    }

    #[Route('/generate-card-name', name: 'generate_card_name', methods: ['GET'])]
    public function generateCardName(CardNameGenerator $cardNameGenerator): JsonResponse
    {
        $generatedName = $cardNameGenerator->generateRandomName();
        return $this->json(['name' => $generatedName]);
    }


    #[Route('/cartes/ajouter', name: 'card_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $card = new Card();
        $card->setCreatedAt(new \DateTimeImmutable());
        $card->setUser($this->getUser());
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($card);
            $em->flush();
            return $this->redirectToRoute('card_list');
        }

        return $this->render('card/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cartes/editer/{id}', name: 'card_edit')]
    public function editer($id, EntityManagerInterface $em, Request $request): Response
    {
        $card = $em->getRepository(Card::class)->find($id);
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('/api/cards', name: 'card_api')]
    public function generateImage(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $cards = $em->getRepository(Card::class)->findAll();
        $pathImages = 'https://localhost:8000/images/cards/';

        $context = [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'attackPower',
                'type' => ['id', 'name'],
                'class' => ['id', 'name'],
                'user' => ['id', 'username'],
                'imageName'
            ],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ];

        $cardsArray = json_decode($serializer->serialize($cards, 'json', $context), true);

        $cards = [];
        foreach ($cardsArray as $card) {
            if (isset($card['imageName'])) {
                $card['imagePath'] = $pathImages . $card['imageName'];
            }
            $cards[] = $card;
        }

        return $this->json($cards);
    }
}
