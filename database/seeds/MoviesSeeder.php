<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        DB::table('movies')->truncate();

        $movies =[];
        for ($i = 0; $i < 100; $i++) {
            $movies[] = [
                'title' => $faker->words($faker->numberBetween(1, 3), true),
                'duration' => $faker->numberBetween(70, 350),
                'format' => $faker->randomElement(['mp4', 'webm', 'mkv', 'flv',]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('movies')->insert($movies);
    }
}
