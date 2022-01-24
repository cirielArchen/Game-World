<?php

declare(strict_types=1);

namespace App\Repository;

interface GameRepository
{
    public const TYPE_DEFAULT = 'game';
    public const TYPE_ALL = 'all';

    public const TYPES_ARRAY = [
            'all',
            'game',
            'dlc',
            'demo',
            'episode',
            'mod',
            'movie',
            'music',
            'series',
            'video',
    ];

    public function get(int $id);
    public function all();
    public function allPaginated(int $limit);
    public function best();
    public function stats();
    public function scoreStats();
    public function filterBy(?string $phrase, string $type = self::TYPE_DEFAULT, int $limit = 15);
}
