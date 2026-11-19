<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/games', format: 'json')]
final class GameApiController extends AbstractController
{
    #[Route('', name: 'api_games', methods: ['GET'])]
    public function index(GameRepository $games): JsonResponse
    {
        return $this->json(array_map($this->record(...), $games->catalog()));
    }

    #[Route('/{id<\d+>}', name: 'api_game', methods: ['GET'])]
    public function detail(int $id, GameRepository $games): JsonResponse
    {
        $game = $games->find($id);
        if (!$game) {
            return $this->json(['error' => 'Гру не знайдено'], 404);
        }
        return $this->json($this->record($game));
    }

    private function record(Game $game): array
    {
        return [
            'id' => $game->getId(),
            'title' => $game->getTitle(),
            'category' => $game->getCategory()->getName(),
            'minPlayers' => $game->getMinPlayers(),
            'maxPlayers' => $game->getMaxPlayers(),
            'minutes' => $game->getMinutes(),
            'description' => $game->getDescription(),
        ];
    }
}
