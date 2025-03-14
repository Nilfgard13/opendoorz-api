<?php

namespace Database\Seeders;

use App\Models\Nomor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RotatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['username' => 'Faishal Habib', 'nomor' => '6282155288328'],
            ['username' => 'Akmal', 'nomor' => '6281944013990'],
            ['username' => 'Farid', 'nomor' => '6282141569504'],
        ];

        foreach ($data as $item) {
            Nomor::create($item);
        }
    }
}
