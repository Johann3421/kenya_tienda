<?php

namespace Database\Seeders;

use App\Models\SerialReward;
use Illuminate\Database\Seeder;

class SerialRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'title' => 'USB',
                'description' => 'Memoria USB Regalo por ser un cliente fiel.',
                'attempt_threshold' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Parlante',
                'description' => 'Parlante Bluetooth Regalo por ser un cliente Maravilloso.',
                'attempt_threshold' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Teclado',
                'description' => 'Teclado Regalo por ser un cliente Entusiasta.',
                'attempt_threshold' => 10,
                'is_active' => true,
            ],
            [
                'title' => 'Audifono',
                'description' => 'Audífono Regalo por ser un cliente Estupendo.',
                'attempt_threshold' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($rewards as $reward) {
            SerialReward::firstOrCreate(
                ['title' => $reward['title']],
                $reward
            );
        }
    }
}
