<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\CategoryLocation;
use App\Http\Controllers\Controller;

class CategoryLocationController extends Controller
{
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

    // Update a user
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
