<?php

declare(strict_types=1);

namespace App\Repository\Builder;

use Illuminate\Support\Facades\DB;
use App\Repository\GameRepository as GameRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use stdClass;

class GameRepository implements GameRepositoryInterface
{
    public function get(int $id)
    {
        $data = DB::table('games')
            ->join('genres', 'games.genre_id', '=', 'genres.id')
            ->join('publishers', 'games.publisher_id', '=', 'publishers.id')
            ->select(
                'games.id', 'games.title', 'games.score', 'games.description',
                'genres.id as genre_id', 'genres.name as genre_name',
                'publishers.id as publisher_id', 'publishers.name as publisher_name'
            )
            ->where('games.id', $id)
            ->first();

        return $this->mapping($data);
    }

    public function all()
    {
        return DB::table('games')
            ->join('genres', 'games.genre_id', '=', 'genres.id')
            ->join('publishers', 'games.publisher_id', '=', 'publishers.id')
            ->select(
                'games.id', 'games.title', 'games.score',
                'genres.id as genre_id', 'genres.name as genre_name',
                'publishers.id as publisher_id', 'publishers.name as publisher_name'
            )
            ->latest('games.created_at')
            ->get()
            ->map(fn($row) => $this->mapping($row));
    }

    public function allPaginated(int $limit)
    {
        $pageName = 'page';
        $currentPage = Paginator::resolveCurrentPage($pageName);

        $baseQuery = DB::table('games')
            ->join('genres', 'games.genre_id', '=', 'genres.id')
            ->join('publishers', 'games.publisher_id', '=', 'publishers.id');

        $total = $baseQuery->count();

        $data = collect();
        if($total) {
            $data = $baseQuery
            ->select(
                'games.id', 'games.title', 'games.score',
                'genres.id as genre_id', 'genres.name as genre_name',
                'publishers.id as publisher_id', 'publishers.name as publisher_name'
            )
            ->latest('games.created_at')
            ->forPage($currentPage, $limit)
            ->get()
            ->map(fn($row) => $this->mapping($row));
        }

        return new LengthAwarePaginator(
            $data,
            $total,
            $limit,
            $currentPage,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    public function best()
    {
        return DB::table('games')
            ->join('genres', 'games.genre_id', '=', 'genres.id')
            ->join('publishers', 'games.publisher_id', '=', 'publishers.id')
            ->select(
                'games.id', 'games.title', 'games.score', 'games.description',
                'genres.id as genre_id', 'genres.name as genre_name',
                'publishers.id as publisher_id', 'publishers.name as publisher_name'
            )
            ->where('score', '>', 9)
            ->orderBy('score', 'desc')
            ->get()
            ->map(fn($row) => $this->mapping($row));
    }

    public function stats()
    {
        return [
            'count' => DB::table('games')->count(),
            'countScoreGtFive' => DB::table('games')->where('score', '>', 5)->count(),
            'max' => DB::table('games')->max('score'),
            'min' => DB::table('games')->min('score'),
            'avg' => DB::table('games')->avg('score')
        ];
    }

    public function scoreStats()
    {
        return DB::table('games')
            ->select(DB::raw('count(*) as count'), 'score')
            ->having('score', '>', 6)
            ->groupBy('score')
            ->orderBy('score', 'desc')
            ->get();
    }

    private function mapping(stdClass $data): stdClass
    {
        $genre = new stdClass();
        $genre->id = $data->genre_id;
        $genre->name = $data->genre_name;

        $publisher = new stdClass();
        $publisher->id = $data->publisher_id;
        $publisher->name = $data->publisher_name;

        $data->genre = $genre;
        $data->publisher = $publisher;

        unset(
            $data->genre_id,
            $data->genre_name,
            $data->publisher_id,
            $data->publisher_name
        );

        return $data;
    }
}
