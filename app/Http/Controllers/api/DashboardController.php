<?php

namespace App\Http\Controllers\api;

use App\Models\Property;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/home-admin",
     *     tags={"Dashboard"},
     *     summary="Menghitung jumlah properti berdasarkan status",
     *     description="Mengambil jumlah properti berdasarkan status seperti 'sold', 'reserved', 'on progress', 'available', serta total properti",
     *     @OA\Response(
     *         response=200,
     *         description="Property count retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Property count retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="property_counts", type="object",
     *                     @OA\Property(property="sold", type="integer", example=10),
     *                     @OA\Property(property="on_reserved", type="integer", example=5),
     *                     @OA\Property(property="on_progress", type="integer", example=8),
     *                     @OA\Property(property="available", type="integer", example=12),
     *                     @OA\Property(property="total", type="integer", example=35)
     *                 ),
     *                 @OA\Property(property="landing_page", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="address", type="string", example="Jl. Sudirman No. 10, Jakarta"),
     *                     @OA\Property(property="number", type="string", example="08123456789"),
     *                     @OA\Property(property="email", type="string", example="contact@realestate.com")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function countProperties()
    {
        $propertyCounts = [
            'sold' => Property::where('status', 'sold')->count(),
            'on_reserved' => Property::where('status', 'reserved')->count(),
            'on_progress' => Property::where('status', 'on progress')->count(),
            'available' => Property::where('status', 'available')->count(),
            'total' => Property::count()
        ];

        $landingPage = LandingPage::find(1);

        return response()->json([
            'success' => true,
            'message' => 'Property count retrieved successfully',
            'data' => [
                'property_counts' => $propertyCounts,
                'landing_page' => $landingPage
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/landing-page-update",
     *     tags={"Dashboard"},
     *     summary="Menambahkan atau memperbarui data Landing Page",
     *     description="Menghapus data landing page yang ada dan menambahkan data baru termasuk gambar dan thumbnail",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="address", type="string", example="Jl. Sudirman No. 10, Jakarta"),
     *                 @OA\Property(property="number", type="string", example="08123456789"),
     *                 @OA\Property(property="email", type="string", example="contact@realestate.com"),
     *                 @OA\Property(property="slogan", type="string", example="Temukan Rumah Impian Anda"),
     *                 @OA\Property(property="url", type="string", example="https://realestate.com"),
     *                 @OA\Property(property="url_ig", type="string", example="https://instagram.com/realestate"),
     *                 @OA\Property(property="experience", type="integer", example=10),
     *                 @OA\Property(property="gmap", type="string", example="https://maps.google.com/example"),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 ),
     *                 @OA\Property(
     *                     property="thumbnails",
     *                     type="array",
     *                     @OA\Items(type="string", format="binary")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Landing Page inserted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Landing Page inserted successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="address", type="string", example="Jl. Sudirman No. 10, Jakarta"),
     *                 @OA\Property(property="number", type="string", example="08123456789"),
     *                 @OA\Property(property="email", type="string", example="contact@realestate.com"),
     *                 @OA\Property(property="slogan", type="string", example="Temukan Rumah Impian Anda"),
     *                 @OA\Property(property="url", type="string", example="https://realestate.com"),
     *                 @OA\Property(property="url_ig", type="string", example="https://instagram.com/realestate"),
     *                 @OA\Property(property="experience", type="integer", example=10),
     *                 @OA\Property(property="gmap", type="string", example="https://maps.google.com/example"),
     *                 @OA\Property(property="images", type="array",
     *                     @OA\Items(type="string", example="storage/landing_page_images/image1.jpg")
     *                 ),
     *                 @OA\Property(property="thumbnails", type="array",
     *                     @OA\Items(type="string", example="storage/landing_page_thumbnails/thumb1.jpg")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insertLandingPage(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|min:0',
            'email' => 'nullable|string|email|max:255',
            'slogan' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'url_ig' => 'nullable|string|max:500',
            'experience' => 'nullable|numeric|min:0',
            'gmap' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'thumbnails.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4016',
        ]);

        // Hapus semua data di tabel LandingPage
        $landingPages = LandingPage::all();
        foreach ($landingPages as $landingPage) {
            // Hapus gambar di storage
            $existingImages = json_decode($landingPage->images, true) ?? [];
            $existingThumbnails = json_decode($landingPage->thumbnails, true) ?? [];

            foreach ($existingImages as $image) {
                Storage::disk('public')->delete($image);
            }

            foreach ($existingThumbnails as $thumbnail) {
                Storage::disk('public')->delete($thumbnail);
            }

            // Hapus record dari database
            $landingPage->delete();
        }

        // Reset Auto Increment ke 1
        DB::statement('ALTER TABLE landing_page AUTO_INCREMENT = 1');

        // Handle upload gambar baru
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('landing_page_images', 'public');
                $images[] = $path;
            }
        }

        $thumbnails = [];
        if ($request->hasFile('thumbnails')) {
            foreach ($request->file('thumbnails') as $thumbnail) {
                $path = $thumbnail->store('landing_page_thumbnails', 'public');
                $thumbnails[] = $path;
            }
        }

        LandingPage::create([
            'address' => $request->address,
            'number' => $request->number,
            'email' => $request->email,
            'slogan' => $request->slogan,
            'url' => $request->url,
            'url_ig' => $request->url_ig,
            'experience' => $request->experience,
            'gmap' => $request->gmap,
            'images' => json_encode($images),
            'thumbnails' => json_encode($thumbnails),
        ]);

        // dd($request->number);

        return response()->json([
            'success' => true,
            'message' => 'Landing Page inserted successfully',
            'data' => $landingPage
        ]);
    }
}
