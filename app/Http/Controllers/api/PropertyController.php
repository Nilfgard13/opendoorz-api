<?php

namespace App\Http\Controllers\api;

use App\Models\Property;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use App\Models\CategoryLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $property = Property::when($search, function ($query, $search) {
            return $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('price', 'LIKE', "%{$search}%")
                ->orWhere('address', 'LIKE', "%{$search}%")
                ->orWhere('parking', 'LIKE', "%{$search}%")
                ->orWhereHas('categoryType', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('categoryLocation', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Property retrieved successfully',
            'data' => $property
        ]);
    }

    public function store(Request $request)
    {
        try {

            $property = $request->validate([
                'title' => 'required|string|max:15',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0|max:9999999999',
                'bedrooms' => 'required|integer|min:1',
                'bathrooms' => 'required|integer|min:0',
                'area' => 'required|integer|min:0',
                'floor' => 'required|integer|min:0',
                'address' => 'required|string|max:500',
                'parking' => 'integer|min:0',
                'status' => 'required|string',
                'category_type_id' => 'required|exists:category_types,id',
                'category_location_id' => 'required|exists:category_locations,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('property_images', 'public');
                    $imagePaths[] = $path;
                }
            }

            // dd($request->images);

            Property::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'area' => $request->area,
                'floor' => $request->floor,
                'address' => $request->address,
                'parking' => $request->parking,
                'status' => $request->status,
                'category_type_id' => $request->category_type_id,
                'category_location_id' => $request->category_location_id,
                'images' => json_encode($imagePaths),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Property created successfully',
                'data' => $property,
                'images' => $imagePaths
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

    public function update(Request $request, $id)
    {
        try {
            $property = Property::find($id);
            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $request->validate([
                'title' => 'required|string|max:15',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0|max:9999999999',
                'bedrooms' => 'required|integer|min:1',
                'bathrooms' => 'required|integer|min:0',
                'area' => 'required|integer|min:0',
                'floor' => 'required|integer|min:0',
                'address' => 'required|string|max:500',
                'parking' => 'nullable|integer|min:0',
                'status' => 'required|string',
                'category_type_id' => 'required|exists:category_types,id',
                'category_location_id' => 'required|exists:category_locations,id',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $property->title = $request->title;
            $property->description = $request->description;
            $property->price = $request->price;
            $property->bedrooms = $request->bedrooms;
            $property->bathrooms = $request->bathrooms;
            $property->area = $request->area;
            $property->floor = $request->floor;
            $property->address = $request->address;
            $property->parking = $request->parking;
            $property->status = $request->status;
            $property->category_type_id = $request->category_type_id;
            $property->category_location_id = $request->category_location_id;

            $existingImages = json_decode($property->images) ?? [];

            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $deletedImage) {

                    Storage::disk('public')->delete($deletedImage);

                    $existingImages = array_filter($existingImages, function ($image) use ($deletedImage) {
                        return $image !== $deletedImage;
                    });
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('property_images', 'public');
                    $existingImages[] = $path;
                }
            }

            $property->images = json_encode(array_values($existingImages));

            $property->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $property
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
        try {
            $property = Property::findOrFail($id);
            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($property->images) {
                $images = json_decode($property->images, true);
                foreach ($images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            $property->delete();
            return response()->json(['status' => 'success', 'message' => 'Property deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }
}
