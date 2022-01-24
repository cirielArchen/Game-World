<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GenresSeeder::class,
            GamesSeeder::class,
            PublisherSeeder::class,
            MoviesSeeder::class,
            BooksSeeder::class,
        ]);
    }
}
