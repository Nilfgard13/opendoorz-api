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

        // Jika ada input pencarian, filter data
        $reviews = Review::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('nomor', 'LIKE', "%{$search}%")
                ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        })->get();

        $title = 'Review Admin';

        return view('admin.review', compact('reviews', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
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

        return redirect()->route('user.index')->with('success', 'Review Anda Terkirim');
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return redirect()->back()->with('error', 'User not found');
        }

        $review->delete();
        return redirect()->route('review.index')->with('success', 'Review deleted successfully');
    }
}
