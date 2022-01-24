<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        DB::table('games')->truncate();

        $games =[];
        for ($i = 0; $i < 100; $i++) {
            $games[] = [
                'title' => $faker->words($faker->numberBetween(1, 3), true),
                'description' => $faker->sentence,
                'publisher_id' => $faker->numberBetween(1, 7),
                'genre_id' => $faker->numberBetween(1, 5),
                'release_date' => Carbon::today()->subDays(rand(0, 365)),
                'rating' => $faker->numberBetween(0, 10),
                'sold' => $faker->numberBetween(1000, 300000),
                'score' => $faker->numberBetween(1, 10),
                'book_id' => $i+1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('games')->insert($games);
    }
}
