<?php

namespace App\Providers;

use App\Model\Game;
use App\Repository\GameRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\Eloquent\GameRepository as EloquentGameRepository;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*
        $this->app->bind(
            GameRepository::class, EloquentGameRepository::class
        );
        */
        $this->app->bind(
            GameRepository::class,
            function ($app) {
                return new EloquentGameRepository(
                    $app->make(Game::class)
                );
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
