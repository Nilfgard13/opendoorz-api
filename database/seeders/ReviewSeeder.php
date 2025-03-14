<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Akmal Hanif', 'email' => 'akmal@example.com', 'nomor' => '080890908', 'deskripsi' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam voluptatem eum aliquam illum fugit, laboriosam praesentium perspiciatis quisquam. Veniam officiis repellat laudantium ratione quas neque consequuntur voluptate fugiat recusandae laboriosam!', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ahmad Syahdan', 'email' => 'syahdan@example.com', 'nomor' => '080890908', 'deskripsi' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam voluptatem eum aliquam illum fugit, laboriosam praesentium perspiciatis quisquam. Veniam officiis repellat laudantium ratione quas neque consequuntur voluptate fugiat recusandae laboriosam!', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Wafiy Anwarul', 'email' => 'wafiy@example.com', 'nomor' => '080890908', 'deskripsi' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aperiam voluptatem eum aliquam illum fugit, laboriosam praesentium perspiciatis quisquam. Veniam officiis repellat laudantium ratione quas neque consequuntur voluptate fugiat recusandae laboriosam!', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($data as $item) {
            Review::create($item);
        }
    }
}
