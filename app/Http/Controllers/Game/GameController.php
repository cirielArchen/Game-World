<?php

namespace App\Http\Controllers\Game;

use App\Model\Game;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Facade\Game as FacadeGame;
use App\Repository\GameRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // CRUD
    // C - create
    // R - read
    // U - update
    // D - delete

    private GameRepository $gameRepository;

    public function __construct(GameRepository $repository)
    {
        $this->gameRepository = $repository;
    }

    public function index(Request $request): View
    {
        $phrase = $request->get('phrase');
        $type = $request->get('type', GameRepository::TYPE_DEFAULT);
        $limit = $request->get('limit', 15);

        $filteredGames = $this->gameRepository->filterBy($phrase, $type, $limit);
        //$games = $this->gameRepository->allPaginated(10);

        return view('game.list', [
            'games' => $filteredGames,
            'phrase' => $phrase,
            'type' => $type,
        ]);
    }

    public function dashboard(Request $request): View
    {
        $bestGames = $this->gameRepository->best();

        $stats = $this->gameRepository->stats();

        $scoreStats = $this->gameRepository->scoreStats();

        return view('game.dashboard', [
            'bestGames' => $bestGames,
            'scoreStats' => $scoreStats,
            'stats' => $stats,
        ]);
    }

    public function show(int $gameId): View
    {
        $user = Auth::user();
        $userHasGame = $user->hasGame($gameId);

        $game = $this->gameRepository->get($gameId);

        return view('game.show', [
            'game' => $game,
            'userHasGame' => $userHasGame,
        ]);
    }
}
