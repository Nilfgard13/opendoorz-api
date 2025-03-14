<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Malang', 'description' => 'ini adalah kota Malang'],
            ['name' => 'Blitar', 'description' => 'ini adalah kota Blitar'],
            ['name' => 'Tulungagung', 'description' => 'ini adalah kota Tulungagung'],
        ];

        foreach ($data as $item) {
            CategoryLocation::create($item);
        }
    }
}
