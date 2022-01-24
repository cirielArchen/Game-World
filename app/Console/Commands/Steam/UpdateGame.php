<?php

namespace App\Console\Commands\Steam;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;

class UpdateGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:update-games {gameNameOrId?} {load=no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private Factory $httpClient;

    private string $pathToGameListFile = './storage/app/steam-update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Factory $httpClient)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gameNameOrId = $this->argument('gameNameOrId');
        $load = $this->argument('load');

        if(($load === 'yes') || ($load === 'y'))
        {
            $this->loadGameList();
        } else {
            $gameList = file_get_contents($this->pathToGameListFile);
            $gameList = json_decode($gameList, true);

            $gamesInDB = DB::table('games')
                ->select('steam_appid')
                ->pluck('steam_appid')
                ->toArray();

            $gamesInDB = array_flip($gamesInDB);

            $genres = DB::table('genres')
                ->select()
                ->get()
                ->toArray();

            foreach ($genres ?? [] as $row) {
                $this->genres[$row->id] = (array) $row;
            }

            $progressDb = $this->output->createProgressBar(count($gameList));

            if($gameNameOrId === null)
            {
                foreach($gameList as $game)
                {
                    sleep(1);
                    $steamGameDetailsUrl = config('steam.api.games.details');
                    $response = $this->httpClient->get(
                    $steamGameDetailsUrl, [
                        'appids' => $game['appid'],
                        'l' => 'en'
                    ]);

                    if($response->failed())
                    {
                        $this->error('Request failed. Game Id:' . $game['appid'] .'Status code: ' . $response->status());
                        continue;
                    }

                    $data = $response->json();

                    if ($data[$game['appid']]['success'] === false) {
                        $this->error('ERROR! Game:' . $game['appid'] . ' - No Data.');
                        continue;
                    }

                    try {
                        $this->update($data);
                    } catch (\Throwable $e) {
                        dump($data);
                        dump($e);
                        continue;
                    }
                    $progressDb->advance();
                    $this->info(" - " . $game['appid'] . ": " .$game['name']);
                }
                $progressDb->finish();
                $this->info('Games updated');
                return 0;
            } else {
                foreach($gameList as $game)
                {
                    if($gameNameOrId === $game['appid'] || $gameNameOrId === $game['name'])
                    {
                        $steamGameDetailsUrl = config('steam.api.games.details');
                        $response = $this->httpClient->get(
                        $steamGameDetailsUrl, [
                            'appids' => $game['appid'],
                            'l' => 'en'
                        ]);

                        if($response->failed())
                        {
                            $this->error('Request failed. Game Id:' . $game['appid'] .'Status code: ' . $response->status());
                            exit;
                        }

                        $data = $response->json();

                        if ($data[$game['appid']]['success'] === false) {
                            $this->error('ERROR! Game:' . $game['appid'] . ' - No Data.');
                            exit;
                        }

                        try {
                            $this->update($data);
                        } catch (\Throwable $e) {
                            dump($data);
                            dump($e);
                            exit;
                        }

                        $this->info('Game: '. $game['name'] . ' found and updated');
                        return 0;
                    }
                }
                $this->info('Game'. $gameNameOrId .'not found');
            }
        }
        return 0;
    }

    private function loadGameList()
    {
        $steamAllGamesUrl = config('steam.api.games.all');
        $response = $this->httpClient->get($steamAllGamesUrl);

        if($response->failed())
        {
            $this->error('Request failed. Status code: ' . $response->status());
            exit;
        }

        $jsonResponse = $response->json();
        $gameList = $jsonResponse['applist']['apps'];
        $jsonGameList = json_encode($gameList);
        file_put_contents($this->pathToGameListFile, $jsonGameList);
        $this->info('File loaded with games from steam');
    }

    private function update($data)
    {
        DB::transaction(function () use ($data) {

            $data = array_shift($data);
            if ($data['success'] !== true) {
                return;
            }

            $data = $data['data'];

            $game = [
                'steam_appid' => $data['steam_appid'],
                'relation_id' => !empty($data['fullgame']) ? (int) $data['fullgame']['appid'] : null,
                'name' => $data['name'],
                'type' => $data['type'],

                'description' => $data['detailed_description'],
                'short_description' => $data['short_description'],
                'about' => $data['about_the_game'],
                'image' => $data['header_image'],
                'website' => $data['website'],

                'price_amount' => $data['price_overview']['initial'] ?? null,
                'price_currency' => $data['price_overview']['currency'] ?? null,

                'metacritic_score' => $data['metacritic']['score'] ?? null,
                'metacritic_url' => $data['metacritic']['url'] ?? null,
                'release_date' => $data['release_date']['date'],
                'languages' => $data['supported_languages'] ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            $gameId = DB::table('games')->insertGetId($game);

            foreach ($data['genres'] ?? [] as $genre) {
                if (empty($this->genres[$genre['id']])) {
                    $genreData = [
                        'id' => $genre['id'],
                        'name' => $genre['description'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $result = DB::table('genres')->insert($genreData);
                    $this->genres[$genreData['id']] = $genreData;
                }

                DB::table('gameGenres')->insert([
                    'game_id' => $gameId,
                    'genre_id' => $genre['id']
                ]);
            }

            foreach ($data['publishers'] ?? [] as $publisher) {
                if (empty($this->publishers[$publisher])) {
                    $publisherData = [
                        'name' => $publisher,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $publisherId = DB::table('publishers')->insertGetId($publisherData);
                    $publisherData['id'] = $publisherId;
                    $this->publishers[$publisher] = $publisherData;
                }

                $publisherId = $this->publishers[$publisher]['id'];

                DB::table('gamePublishers')->insert([
                    'game_id' => $gameId,
                    'publisher_id' => $publisherId
                ]);
            }

            foreach ($data['developers'] ?? [] as $developer) {
                if (empty($this->developers[$developer])) {
                    $developerData = [
                        'name' => $developer,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $developerId = DB::table('developers')->insertGetId($developerData);
                    $developerData['id'] = $developerId;
                    $this->developers[$developer] = $developerData;
                }

                $developerId = $this->developers[$developer]['id'];

                DB::table('gameDevelopers')->insert([
                    'game_id' => $gameId,
                    'developer_id' => $developerId
                ]);
            }

            foreach ($data['screenshots'] ?? [] as $screenshot) {
                DB::table('screenshots')->insert([
                    'game_id' => $gameId,
                    'thumbnail' => $screenshot['path_thumbnail'],
                    'url' => $screenshot['path_full'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            foreach ($data['movies'] ?? [] as $movie) {
                DB::table('movies')->insertOrIgnore([
                    'game_id' => $gameId,
                    'original_id' => $movie['id'],
                    'name' => $movie['name'],
                    'highlight' => $movie['highlight'],
                    'thumbnail' => $movie['thumbnail'],
                    'webm_480' => $movie['webm']['480'],
                    'webm_url' => $movie['webm']['max'],
                    'mp4_480' => $movie['mp4']['480'],
                    'mp4_url' => $movie['mp4']['max'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        });
    }
}
