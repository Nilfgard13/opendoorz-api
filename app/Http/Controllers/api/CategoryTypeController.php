<?php

namespace App\Http\Controllers\api;

use App\Models\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/category-admin",
     *     tags={"Category Types"},
     *     summary="Menampilkan daftar Lokasi",
     *     description="Mengambil daftar Lokasi dengan fitur pencarian berdasarkan name atau deskripsi",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filter Lokasi berdasarkan name atau deskripsi",
     *         required=false,
     *         @OA\Schema(type="string", example="villa")
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
        $category = CategoryType::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Type retrieved successfully',
            'data' => $category
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/category-create",
     *     summary="Create a new category type",
     *     tags={"Category Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Type created successfully"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Something went wrong")
     * )
     */
    public function store(Request $request)
    {
        try {
            $category = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);
            // dd($request);
            CategoryType::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Type created successfully',
                'data' => $category
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
     *     path="/api/category-update/{id}",
     *     summary="Update an existing category type",
     *     tags={"Category Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category Type ID",
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
     *     @OA\Response(response=200, description="Type updated successfully"),
     *     @OA\Response(response=404, description="Type not found"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Something went wrong")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $category = CategoryType::find($id);
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type not found'
                ], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            $category->name = $request->name;
            $category->description = $request->description;

            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Type updated successfully',
                'data' => $category
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
     *     path="/api/category-delete/{id}",
     *     summary="Delete a category type",
     *     tags={"Category Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category Type ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Type deleted successfully"),
     *     @OA\Response(response=404, description="Type not found")
     * )
     */
    public function destroy($id)
    {
        $category = CategoryType::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Type not found'
            ], 404);
        }

        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Type deleted successfully'
        ]);
    }
}
