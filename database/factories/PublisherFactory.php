<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Publisher;
use Faker\Generator as Faker;

$factory->define(Publisher::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});
