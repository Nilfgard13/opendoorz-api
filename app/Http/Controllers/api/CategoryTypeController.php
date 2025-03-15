<?php

namespace App\Http\Controllers\api;

use App\Models\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryTypeController extends Controller
{
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

    // Update a user
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
