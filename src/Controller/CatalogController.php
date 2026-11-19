<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogController extends AbstractController
{
    #[Route('/', name: 'catalog', methods: ['GET'])]
    public function index(GameRepository $games): Response
    {
        return $this->render('catalog/index.html.twig', ['games' => $games->catalog()]);
    }

    #[Route('/games/{id<\d+>}', name: 'game_detail', methods: ['GET'])]
    public function detail(Game $game): Response
    {
        return $this->render('catalog/detail.html.twig', ['game' => $game]);
    }
}
