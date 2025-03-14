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

        $title = 'Category Location Admin';

        return view('admin.categorylocation', compact('categorylocation', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        // dd($request);
        CategoryLocation::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categorylocation.index')->with('success', 'Category created successfully');
    }

    // Update a user
    public function update(Request $request, $id)
    {
        $categorylocation = CategoryLocation::find($id);
        if (!$categorylocation) {
            return redirect()->back()->with('error', 'Category not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $categorylocation->name = $request->name;
        $categorylocation->description = $request->description;

        $categorylocation->save();

        return redirect()->route('categorylocation.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $categorylocation = CategoryLocation::find($id);
        if (!$categorylocation) {
            return redirect()->back()->with('error', 'User not found');
        }

        $categorylocation->delete();
        return redirect()->route('categorylocation.index')->with('success', 'Category deleted successfully');
    }
}
