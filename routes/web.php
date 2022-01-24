<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Game;
use App\Http\Controllers\User;

//use App\Http\Controllers\Home\MainPage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['middleware' => ['auth']], function() {
Route::middleware(['auth'])->group(function() {

    Route::get('/', 'Home\MainPage')
        ->name('home.mainPage');

    // USER - ME ROUTE
    Route::group([
            'prefix' => 'me',
            'as' => 'me.'
    ],  function() {
        Route::get('profile', 'User\UserController@profile')
            ->name('profile');

        Route::get('edit', 'User\UserController@edit')
            ->name('edit');

        Route::post('update', 'User\UserController@update')
            ->name('update');

        Route::get('games', 'User\GameController@list')
            ->name('games.list');

        Route::post('games', 'User\GameController@add')
            ->name('games.add');

        Route::delete('games', 'User\GameController@remove')
            ->name('games.remove');

        Route::post('games/rate', 'User\GameController@rate')
            ->name('games.rate');
    });

    // USERS ROUTE
    Route::get('users', 'UserController@list')
        ->name('get.users');

    Route::get('users/{userId}', 'UserController@show')
        ->name('get.user.show');

    /*
    Route::get('users/{id}/address', 'User\ShowAddress')
        ->where(['id' => '[0-9]+'])
        ->name('get.users.address');
    */

    // GAMES BUILDER ROUTE
    Route::group([
        'prefix' => 'b/games',
        'namespace' => 'Game',
        'as' => 'games.b.'
        ], function () {

    Route::get('dashboard', 'BuilderController@dashboard')
        ->name('dashboard');

    Route::get('', 'BuilderController@index')
        ->middleware('requestPage')
        ->name('list');

    Route::get('{game}', 'BuilderController@show')
        ->name('show');
    });

    // REPOSITORY CONTROLLER ROUTE
    Route::group([
        'prefix' => 'games',
        'namespace' => 'Game',
        'as' => 'games.'
        ], function () {

    Route::get('dashboard', 'GameController@dashboard')
        ->name('dashboard');

    Route::get('', 'GameController@index')
        ->middleware('requestPage')
        ->name('list');

    Route::get('{game}', 'GameController@show')
        ->name('show');
    });

    // GAMES ELOQUENT ROUTE
    Route::group([
        'prefix' => 'e/games',
        'namespace' => 'Game',
        'as' => 'games.e.',
        //'middleware' => ['profiling'],
        ], function () {

        Route::middleware(['profiling', 'requestPage'])->group(
            function() {
                Route::get('dashboard', 'EloquentController@dashboard')
                    ->name('dashboard');

                Route::get('', 'EloquentController@index')
                    ->name('list');

                Route::get('{game}', 'EloquentController@show')
                    ->name('show');
            }
        );
    });
});

Auth::routes();
