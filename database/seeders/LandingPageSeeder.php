<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'address' => 'Jl. Puri Indah No. 45, Malang',
                'number' => '0813-5747-7967',
                'email' => "opendoorz@gmail.com",
                'slogan' => null,
                'images' => null,
                'url' => 'https://www.youtube.com/watch?v=PZddOGwjtFk&t=25s',
                'url_ig' => 'https://www.instagram.com/opendoorz.id',
                'thumbnails' => null,
                'experience' => 4,
                'gmap' => null,
            ],
        ];

        foreach ($data as $item) {
            LandingPage::create($item);
        }
    }
}
