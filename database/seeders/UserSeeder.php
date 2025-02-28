<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'dni' => '47012503',
            'nombres' => Str::random(10),
            'ape_paterno' => Str::random(10),
            'ape_materno' => Str::random(10),
            'telefono' => '958021778',
            'email' => 'kenya@gmail.com',
            'username' => 'kenya',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
