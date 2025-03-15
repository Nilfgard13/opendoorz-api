<?php

namespace App\Http\Controllers\api;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/review-admin",
     *     tags={"Reviews"},
     *     summary="Menampilkan daftar Review",
     *     description="Mengambil daftar Review dengan fitur pencarian berdasarkan nama, email, nomor, atau deskripsi",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filter Review berdasarkan nama, email, nomor, atau deskripsi",
     *         required=false,
     *         @OA\Schema(type="string", example="Akmal")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reviews retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reviews retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="nomor", type="string", example="08123456789"),
     *                     @OA\Property(property="deskripsi", type="string", example="Pelayanan sangat baik!")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $reviews = Review::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('nomor', 'LIKE', "%{$search}%")
                ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Review retrieved successfully',
            'data' => $reviews
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/review-create",
     *     tags={"Reviews"},
     *     summary="Membuat Review baru",
     *     description="Menyimpan review baru ke dalam database",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","nomor","deskripsi"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="nomor", type="string", example="08123456789"),
     *             @OA\Property(property="deskripsi", type="string", example="Pelayanan sangat baik!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="nomor", type="string", example="08123456789"),
     *                 @OA\Property(property="deskripsi", type="string", example="Pelayanan sangat baik!")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error"),
     *             @OA\Property(property="error", type="string", example="Error details")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $review = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:reviews',
                'nomor' => 'required|numeric',
                'deskripsi' => 'required|string|max:255',
            ]);

            // $title = 'Review Admin';

            Review::create([
                'name' => $request->name,
                'email' => $request->email,
                'nomor' => $request->nomor,
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/review-admin/{id}",
     *     tags={"Reviews"},
     *     summary="Menghapus Review",
     *     description="Menghapus Review berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Review yang akan dihapus",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Review not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $review->delete();
        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }
}
