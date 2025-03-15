<?php

namespace App\Http\Controllers\api;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
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
