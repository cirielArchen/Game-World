<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        DB::table('books')->truncate();

        $book =[];
        for ($i = 0; $i < 100; $i++) {
            $book[] = [
                'title' => $faker->words($faker->numberBetween(1, 3), true),
                'author' => $faker->firstName." ".$faker->lastName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('books')->insert($book);
    }
}
