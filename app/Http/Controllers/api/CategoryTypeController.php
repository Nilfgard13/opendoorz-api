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

        $title = 'Category Type Admin';

        return view('admin.category', compact('category', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        // dd($request);
        CategoryType::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categorytype.index')->with('success', 'Category created successfully');
    }

    // Update a user
    public function update(Request $request, $id)
    {
        $category = CategoryType::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $category->name = $request->name;
        $category->description = $request->description;

        $category->save();

        return redirect()->route('categorytype.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = CategoryType::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'User not found');
        }

        $category->delete();
        return redirect()->route('categorytype.index')->with('success', 'Category deleted successfully');
    }
}
