<?php

namespace App\Http\Controllers\api;

use App\Models\Property;
use App\Models\LandingPage;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingpageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/",
     *     tags={"Landing Page"},
     *     summary="Retrieve data for home page view (company profile)",
     *     description="Mengambil data properti terbaru, jumlah properti, jumlah kategori, dan data landing page",
     *     @OA\Response(
     *         response=200,
     *         description="Home data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Home data retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="property", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Modern House"),
     *                         @OA\Property(property="status", type="string", example="available"),
     *                         @OA\Property(property="price", type="integer", example=500000000),
     *                         @OA\Property(property="location", type="string", example="Jakarta"),
     *                     )
     *                 ),
     *                 @OA\Property(property="property_counts", type="object",
     *                     @OA\Property(property="sold", type="integer", example=5),
     *                     @OA\Property(property="total", type="integer", example=20)
     *                 ),
     *                 @OA\Property(property="category_counts", type="integer", example=10),
     *                 @OA\Property(property="landing_page", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Welcome to Our Real Estate"),
     *                     @OA\Property(property="content", type="string", example="Find your dream home with us."),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function homeIndex(Request $request)
    {
        $propertyCounts = [
            'sold' => Property::where('status', 'sold')->count(),
            'total' => Property::count()
        ];
        $categoryCounts = CategoryType::all()->count();
        $property = Property::where('status', 'available')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $landingPage = LandingPage::find(1);

        return response()->json([
            'success' => true,
            'message' => 'Home data retrieved successfully',
            'data' => [
                'property' => $property,
                'property_counts' => $propertyCounts,
                'category_counts' => $categoryCounts,
                'landing_page' => $landingPage,
            ]
        ]);
    }

    public function contactIndex(Request $request)
    {
        $landingPage = LandingPage::find(1);

        return response()->json([
            'success' => true,
            'message' => 'Contact page data retrieved successfully',
            'data' => [
                'landing_page' => $landingPage,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/property",
     *     tags={"Landing Page"},
     *     summary="Dsiplaying List property",
     *     description="Mengambil daftar properti yang tersedia dengan fitur pencarian berdasarkan judul, deskripsi, harga, alamat, tempat parkir, dan kategori",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filter properti berdasarkan kata kunci",
     *         required=false,
     *         @OA\Schema(type="string", example="Malang")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Property data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Property data retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="property", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Modern House"),
     *                         @OA\Property(property="description", type="string", example="A beautiful house with a garden"),
     *                         @OA\Property(property="price", type="integer", example=750000000),
     *                         @OA\Property(property="address", type="string", example="Jl. Sudirman No. 10, Jakarta"),
     *                         @OA\Property(property="parking", type="integer", example=2),
     *                         @OA\Property(property="category_type", type="string", example="Residential"),
     *                         @OA\Property(property="category_location", type="string", example="Jakarta"),
     *                     )
     *                 ),
     *                 @OA\Property(property="types", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Residential")
     *                     )
     *                 ),
     *                 @OA\Property(property="landing_page", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Find Your Dream Home"),
     *                     @OA\Property(property="content", type="string", example="The best properties in town."),
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function propertyIndex(Request $request)
    {
        $search = $request->input('search');
        $landingPage = LandingPage::find(1);
        $types = CategoryType::all();

        $property = Property::where('status', 'available')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('price', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%")
                    ->orWhere('parking', 'LIKE', "%{$search}%")
                    ->orWhereHas('categoryType', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('categoryLocation', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%");
                    });
            })->orderBy('id', 'desc')->paginate(9);

        return response()->json([
            'success' => true,
            'message' => 'Property data retrieved successfully',
            'data' => [
                'property' => $property,
                'types' => $types,
                'landing_page' => $landingPage,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/details-property/{id}",
     *     tags={"Landing Page"},
     *     summary="Displaying property details",
     *     description="Mengambil informasi lengkap dari properti berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID properti",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Property details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Property details retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="property", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Modern House"),
     *                     @OA\Property(property="description", type="string", example="A beautiful house with a garden"),
     *                     @OA\Property(property="price", type="integer", example=750000000),
     *                     @OA\Property(property="address", type="string", example="Jl. Sudirman No. 10, Jakarta"),
     *                     @OA\Property(property="parking", type="integer", example=2),
     *                     @OA\Property(property="category_type", type="string", example="Residential"),
     *                     @OA\Property(property="category_location", type="string", example="Jakarta"),
     *                 ),
     *                 @OA\Property(property="other_properties", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="title", type="string", example="Luxury Apartment"),
     *                         @OA\Property(property="price", type="integer", example=1000000000)
     *                     )
     *                 ),
     *                 @OA\Property(property="landing_page", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Find Your Dream Home"),
     *                     @OA\Property(property="content", type="string", example="The best properties in town."),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function detailsIndex($id)
    {
        $property = Property::where('status', 'available')->findOrFail($id);
        $landingPage = LandingPage::find(1);
        $otherProperties = Property::where('id', '!=', $property->id)
            ->where('status', 'available')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $title = 'Detail Page';

        return response()->json([
            'success' => true,
            'message' => 'Property details retrieved successfully',
            'data' => [
                'property' => $property,
                'other_properties' => $otherProperties,
                'landing_page' => $landingPage,
            ]
        ]);
    }
}
