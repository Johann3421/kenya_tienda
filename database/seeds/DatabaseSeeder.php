<?php

use Database\Seeders\PermissionsSeeder;
use Database\Seeders\SeriesTableSeeder;
use Database\Seeders\UserSeeder;
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
        // $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(ApiSeeder::class);
        $this->call(SeriesTableSeeder::class);
        $this->call([
            PermissionsSeeder::class,
            // Otros seeders...
        ]);
    }
}
