<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Game;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
    return [
        'id' => $faker->numberBetween(1, 1000000),
        'steam_appid' => $faker->randomNumber(),
        'name' => $faker->words(rand(1,3), true),
        'type' => $faker->randomElement(['game', 'dlc', 'demo']),
        'description' => $faker->text,
        'short_description' => $faker->text,
        'about' => $faker->text,
        'image' => $faker->imageUrl(),
        'website' => $faker->url,
        'price_amount' => $faker->numberBetween(1, 5000),
        'price_currency' => $faker->randomElement(['PLN']),
        'metacritic_score' => $faker->numberBetween(1, 100),
        'metacritic_url' => $faker->url,
        'release_date' => $faker->date(),
        'languages' => 'Polish, English',
    ];
});
