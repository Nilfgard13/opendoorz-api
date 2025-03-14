<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "title" => "Grand Mansion",
                "description" => "A stunning mansion with elegant interiors and a vast backyard. The spacious rooms are designed with modern aesthetics while retaining a classic charm. The mansion includes a private cinema, a wine cellar, and a rooftop garden for relaxation.\n\nThe luxurious environment is complemented by top-notch security, a private gym, and a swimming pool. The location provides easy access to shopping malls, schools, and business centers.",
                "price" => 7800000000,
                "bedrooms" => 6,
                "bathrooms" => 5,
                "area" => 500,
                "floor" => 3,
                "address" => "Jl. Mega Kuningan No. 10",
                "status" => "available",
                "category_type_id" => 2,
                "category_location_id" => 1,
                "images" => null
            ],
            [
                "title" => "Elegant Townhouse",
                "description" => "This townhouse offers a perfect blend of modern living and comfort. The house is equipped with smart home features, automated lighting, and an advanced security system. With a spacious living room and an open kitchen concept, it is ideal for families who appreciate modern aesthetics.\n\nLocated in a gated community with 24/7 security, residents can enjoy a peaceful environment while having access to premium facilities such as a clubhouse, jogging track, and a children’s playground.",
                "price" => 2800000000,
                "bedrooms" => 4,
                "bathrooms" => 3,
                "area" => 200,
                "floor" => 2,
                "address" => "Jl. Puri Indah No. 45",
                "status" => "available",
                "category_type_id" => 1,
                "category_location_id" => 2,
                "images" => null
            ],
            [
                "title" => "Beachfront Villa",
                "description" => "A breathtaking beachfront villa offering stunning views of the ocean. The villa is built with an open-concept design, allowing natural light to flood every corner of the home. It features an infinity pool, an outdoor lounge area, and direct access to the beach.\n\nDesigned for ultimate relaxation, the villa includes a spacious master suite with a private balcony overlooking the sea. The interiors boast elegant wood finishes, high ceilings, and premium furnishings, making it the perfect holiday home or investment property.",
                "price" => 5200000000,
                "bedrooms" => 5,
                "bathrooms" => 4,
                "area" => 400,
                "floor" => 1,
                "address" => "Jl. Sunset Bay No. 22",
                "status" => "available",
                "category_type_id" => 2,
                "category_location_id" => 2,
                "images" => null
            ],
            [
                "title" => "Modern Studio Apartment",
                "description" => "A fully furnished studio apartment located in the heart of the city. Perfect for young professionals or students, this apartment offers a compact yet functional space with a comfortable bed, a fully equipped kitchenette, and a modern bathroom.\n\nResidents can enjoy various facilities such as a gym, swimming pool, and a rooftop lounge with panoramic city views. The building has round-the-clock security, high-speed internet, and is within walking distance of shopping malls, cafes, and public transportation hubs.",
                "price" => 600000000,
                "bedrooms" => 1,
                "bathrooms" => 1,
                "area" => 40,
                "floor" => 15,
                "address" => "Jl. Asia Afrika No. 18",
                "status" => "available",
                "category_type_id" => 1,
                "category_location_id" => 1,
                "images" => null
            ],
            [
                "title" => "Rustic Mountain Retreat",
                "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
                "price" => 1900000000,
                "bedrooms" => 3,
                "bathrooms" => 2,
                "area" => 150,
                "floor" => 2,
                "address" => "Jl. Gunung Tidar No. 9",
                "status" => "available",
                "category_type_id" => 2,
                "category_location_id" => 1,
                "images" => null
            ],
            [
                'title' => 'Modern Apartment',
                'description' => 'A beautiful modern apartment in the city center.',
                'price' => 1500000000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 120,
                'floor' => 5,
                'address' => 'Jl. Sudirman No. 45',
                'status' => 'available',
                'category_type_id' => 1,
                'category_location_id' => 2,
                'images' => null, // Kosong
            ],
            [
                'title' => 'Luxury Villa',
                'description' => 'A spacious villa with a private pool.',
                'price' => 4500000000,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area' => 300,
                'floor' => 2,
                'address' => 'Jl. Ubud Raya No. 99',
                'status' => 'available',
                'category_type_id' => 2,
                'category_location_id' => 3,
                'images' => null, // Kosong
            ],
            [
                'title' => 'Minimalist House',
                'description' => 'A cozy minimalist house in a quiet neighborhood.',
                'price' => 900000000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area' => 80,
                'floor' => 1,
                'address' => 'Jl. Anggrek No. 10',
                'parking' => 2,
                'status' => 'sold',
                'category_type_id' => 3,
                'category_location_id' => 1,
                'images' => null, // Kosong
            ],
            [
                'title' => 'Studio Apartment',
                'description' => 'A fully furnished studio apartment.',
                'price' => 500000000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area' => 45,
                'floor' => 10,
                'address' => 'Jl. Thamrin No. 21',
                'parking' => 2,
                'status' => 'available',
                'category_type_id' => 1,
                'category_location_id' => 2,
                'images' => null, // Kosong
            ],
            [
                'title' => 'Beachfront Bungalow',
                'description' => 'A bungalow with direct access to the beach.',
                'price' => 3200000000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 200,
                'floor' => 1,
                'address' => 'Jl. Pantai Indah No. 7',
                'parking' => 2,
                'status' => 'available',
                'category_type_id' => 2,
                'category_location_id' => 2,
                'images' => null, // Kosong
            ],
            [
                'title' => 'Mountain Cabin',
                'description' => 'A beautiful cabin in the mountains.',
                'price' => 1800000000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 150,
                'floor' => 2,
                'address' => 'Jl. Puncak No. 33',
                'parking' => 2,
                'status' => 'available',
                'category_type_id' => 1,
                'category_location_id' => 2,
                'images' => null, // Kosong
            ],
            // [
            //     'title' => 'Townhouse',
            //     'description' => 'A modern townhouse in a gated community.',
            //     'price' => 2200000000,
            //     'bedrooms' => 4,
            //     'bathrooms' => 3,
            //     'area' => 180,
            //     'floor' => 2,
            //     'address' => 'Jl. Harmoni No. 88, Surabaya',
            //     'status' => 'available',
            //     'category_type_id' => 3,
            //     'category_location_id' => 2,
            //     'images' => null, // Kosong
            // ],
            // [
            //     'title' => 'Penthouse',
            //     'description' => 'A luxurious penthouse with city skyline views.',
            //     'price' => 6000000000,
            //     'bedrooms' => 6,
            //     'bathrooms' => 5,
            //     'area' => 400,
            //     'floor' => 20,
            //     'address' => 'Jl. Gatot Subroto No. 77, Jakarta',
            //     'status' => 'available',
            //     'category_type_id' => 2,
            //     'category_location_id' => 2,
            //     'images' => null, // Kosong
            // ],
            // [
            //     'title' => 'Cozy Cottage',
            //     'description' => 'A charming cottage in a peaceful village.',
            //     'price' => 1300000000,
            //     'bedrooms' => 2,
            //     'bathrooms' => 1,
            //     'area' => 90,
            //     'floor' => 1,
            //     'address' => 'Jl. Desa Damai No. 3, Yogyakarta',
            //     'status' => 'available',
            //     'category_type_id' => 1,
            //     'category_location_id' => 1,
            //     'images' => null, // Kosong
            // ],
            // [
            //     'title' => 'Smart Home',
            //     'description' => 'A high-tech smart home with full automation.',
            //     'price' => 5500000000,
            //     'bedrooms' => 5,
            //     'bathrooms' => 4,
            //     'area' => 350,
            //     'floor' => 2,
            //     'address' => 'Jl. Teknologi No. 99, Jakarta',
            //     'status' => 'available',
            //     'category_type_id' => 1,
            //     'category_location_id' => 2,
            //     'images' => null, // Kosong
            // ],
            // [
            //     'title' => 'Farmhouse',
            //     'description' => 'A farmhouse with a large garden and barn.',
            //     'price' => 2500000000,
            //     'bedrooms' => 4,
            //     'bathrooms' => 3,
            //     'area' => 220,
            //     'floor' => 2,
            //     'address' => 'Jl. Tani Makmur No. 5, Malang',
            //     'status' => 'available',
            //     'category_type_id' => 3,
            //     'category_location_id' => 3,
            //     'images' => null, // Kosong
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],
            // [
            //     "title" => "Rustic Mountain Retreat",
            //     "description" => "A charming wooden cabin nestled in the mountains, perfect for those looking to escape the hustle and bustle of city life. The house is surrounded by lush greenery and offers stunning views of the valley. It includes a cozy fireplace, a spacious deck, and a private garden.\n\nThe retreat is ideal for nature lovers, as it provides access to hiking trails, waterfalls, and wildlife. The area experiences cool weather year-round, making it a peaceful sanctuary for relaxation or a weekend getaway.",
            //     "price" => 1900000000,
            //     "bedrooms" => 3,
            //     "bathrooms" => 2,
            //     "area" => 150,
            //     "floor" => 2,
            //     "address" => "Jl. Gunung Tidar No. 9, Bogor",
            //     "status" => "available",
            //     "category_type_id" => 2,
            //     "category_location_id" => 1,
            //     "images" => null
            // ],

        ];

        foreach ($data as $item) {
            Property::create($item);
        }
    }
}
