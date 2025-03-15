<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\CategoryLocation;
use App\Http\Controllers\Controller;

class CategoryLocationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/category-location-admin",
     *     tags={"Category Locations"},
     *     summary="Menampilkan daftar Lokasi",
     *     description="Mengambil daftar Lokasi dengan fitur pencarian berdasarkan name atau deskripsi",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filter Lokasi berdasarkan name atau deskripsi",
     *         required=false,
     *         @OA\Schema(type="string", example="Malang")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Users retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="john_doe"),
     *                     @OA\Property(property="description", type="string", example="........."),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Jika ada input pencarian, filter data
        $categorylocation = CategoryLocation::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Location retrieved successfully',
            'data' => $categorylocation
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/category-location-create",
     *     summary="Create a new category location",
     *     tags={"Category Locations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category location created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $categorylocation = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
            // dd($request);
            CategoryLocation::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location created successfully',
                'data' => $categorylocation
            ], 201);
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
     * @OA\Put(
     *     path="/api/category-location-update/{id}",
     *     summary="Update an existing category location",
     *     tags={"Category Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category location",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category location updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category location not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $categorylocation = CategoryLocation::find($id);
            if (!$categorylocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found'
                ], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            $categorylocation->name = $request->name;
            $categorylocation->description = $request->description;

            $categorylocation->save();

            return response()->json([
                'success' => true,
                'message' => 'Location created successfully',
                'data' => $categorylocation
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
     *     path="/api/category-location-delete/{id}",
     *     summary="Delete a category location",
     *     tags={"Category Locations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category location",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category location deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category location not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $categorylocation = CategoryLocation::find($id);
        if (!$categorylocation) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $categorylocation->delete();
        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully'
        ]);
    }
}
