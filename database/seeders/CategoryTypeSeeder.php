<?php

namespace Database\Seeders;

use App\Models\CategoryType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Appartemen', 'description' => 'appartemen'],
            ['name' => 'Villa Bukit Ubud', 'description' => 'villa'],
            ['name' => 'Rumah Bukit Ubud', 'description' => 'rumah'],
        ];

        foreach ($data as $item) {
            CategoryType::create($item);
        }
    }
}
