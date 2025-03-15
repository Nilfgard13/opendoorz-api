<?php

namespace App\Http\Controllers\api;

use App\Models\Nomor;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *      title="API Documentation",
 *      version="1.0",
 *      description="Dokumentasi API untuk CRUD Admin WA Rotator"
 * )
 *
 * @OA\Tag(
 *     name="Rotator",
 *     description="API untuk mengelola data Admin WA Rotator"
 * )
 */
class NomorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-admin",
     *     tags={"Admins"},
     *     summary="Menampilkan daftar pengguna",
     *     description="Mengambil daftar pengguna dengan fitur pencarian berdasarkan username, email, atau role",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filter Admin berdasarkan username atau nomor hp",
     *         required=false,
     *         @OA\Schema(type="string", example="Akmal")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="username", type="string", example="john_doe"),
     *                     @OA\Property(property="nomor", type="string", example="6281357477967"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $nomors = Nomor::when($search, function ($query, $search) {
            return $query->where('username', 'LIKE', "%{$search}%")
                ->orWhere('nomor', 'LIKE', "%{$search}%");
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $nomors
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/nomor-create",
     *     summary="Create a new nomor",
     *     tags={"Admins"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","nomor"},
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="nomor", type="string", example="6281357477967"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Nomor created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function store(Request $request)
    {
        try {
            $nomors = $request->validate([
                'username' => 'required|string|max:255',
                'nomor' => 'required|numeric',
            ]);
            // dd($request);
            Nomor::create([
                'username' => $request->username,
                'nomor' => $request->nomor,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $nomors
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/nomor-update/{id}",
     *     summary="Update a nomor",
     *     tags={"Admins"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Nomor ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","nomor"},
     *             @OA\Property(property="username", type="string", example="john_updated"),
     *             @OA\Property(property="nomor", type="string", example="6281357477999"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Nomor updated successfully"),
     *     @OA\Response(response=404, description="Nomor not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $nomor = Nomor::find($id);
            if (!$nomor) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $request->validate([
                'username' => 'required|string|max:255',
                'nomor' => 'required|numeric',
            ]);

            $nomor->username = $request->username;
            $nomor->nomor = $request->nomor;

            $nomor->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $nomor
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/nomor-delete/{id}",
     *     summary="Delete a nomor by ID",
     *     tags={"Admins"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Nomor ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Nomor deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Nomor deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nomor not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Nomor not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $nomor = Nomor::find($id);
        if (!$nomor) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $nomor->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function generateLink($id)
    {
        $text = $this->chatShow($id);

        $admins = Nomor::pluck('nomor')->toArray();

        if (empty($admins)) {
            return response()->json([
                'success' => false,
                'message' => 'No admin numbers available'
            ], 400);
        }

        $indexFile = 'admin_index.txt';

        if (!file_exists(storage_path($indexFile))) {
            $currentIndex = 0;
            file_put_contents(storage_path($indexFile), $currentIndex);
        } else {
            $currentIndex = (int)file_get_contents(storage_path($indexFile));
        }

        $adminNumber = $admins[$currentIndex];

        $nextIndex = ($currentIndex + 1) % count($admins);
        file_put_contents(storage_path($indexFile), $nextIndex);

        $url = "https://api.whatsapp.com/send?phone=" . $adminNumber . "&text=" . urlencode($text);
        // session()->flash('generated_url', $url);

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp link generated successfully',
            'data' => ['url' => $url]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/show-link/{id?}",
     *     summary="Generate and show a link",
     *     tags={"Admins"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=false,
     *         description="Optional ID for generating a link",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Link generated successfully"),
     *     @OA\Response(response=400, description="Invalid request"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function showlink($id = null)
    {
        return $this->generateLink($id);
    }

    /**
     * @OA\Get(
     *     path="/api/chat-show/{id}",
     *     summary="Generate a chat message for a property",
     *     tags={"Property"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Property ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Chat message generated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Chat message generated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="🌟 Halo Admin Opendoorz... (chat content)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Property ID is required",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Property ID is required")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Property not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Property not found")
     *         )
     *     )
     * )
     */
    public function chatShow($id = null)
    {
        if ($id === null) {
            return response()->json([
                'success' => false,
                'message' => 'Property ID is required'
            ], 400);
        }

        try {
            $property = Property::findOrFail($id);

            $formattedPrice = number_format($property->price, 0, ',', '.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $detailProduct = url('/details-property/' . $property->id);

        $text = $detailProduct . PHP_EOL . "🌟 Halo Admin Opendoorz" . PHP_EOL . PHP_EOL
            . "Saya tertarik dengan properti *" . $property->title . "* yang tersedia di website." . PHP_EOL . PHP_EOL
            . "🏡 *Nama Properti*: " . $property->title . PHP_EOL
            . "📍 *Lokasi*: " . $property->address . ", " . ($property->categoryLocation->name ?? '') . PHP_EOL
            . "💰 *Harga*: Rp. " . $formattedPrice . PHP_EOL . PHP_EOL
            . "Saya ingin mengetahui lebih lanjut tentang proses pembelian dan detail lainnya." . PHP_EOL
            . "Bisa tolong dibantu untuk informasinya? Terima kasih! 😊";

        return response()->json([
            'success' => true,
            'message' => 'Chat message generated successfully',
            'data' => ['message' => $text]
        ]);
    }
}
