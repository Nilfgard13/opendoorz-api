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
    /**
     * @OA\Get(
     *     path="/api/property-admin",
     *     tags={"Property"},
     *     summary="Retrieved List property",
     *     description="Mengambil daftar properti dengan fitur pencarian berdasarkan title, description, price, address, parking, atau kategori",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filter properti berdasarkan kata kunci",
     *         required=false,
     *         @OA\Schema(type="string", example="Malang")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Property retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Property retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Rumah Mewah"),
     *                     @OA\Property(property="description", type="string", example="Rumah mewah dengan 3 kamar tidur"),
     *                     @OA\Property(property="price", type="integer", example=1500000000),
     *                     @OA\Property(property="address", type="string", example="Jl. Merdeka No.1"),
     *                     @OA\Property(property="parking", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/property-create",
     *     tags={"Property"},
     *     summary="Add new property",
     *     description="Menambahkan properti baru dengan detail lengkap",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","price","bedrooms","bathrooms","area","floor","address","status","category_type_id","category_location_id"},
     *             @OA\Property(property="title", type="string", example="Rumah Minimalis"),
     *             @OA\Property(property="description", type="string", example="Rumah minimalis dengan desain modern"),
     *             @OA\Property(property="price", type="integer", example=1200000000),
     *             @OA\Property(property="bedrooms", type="integer", example=3),
     *             @OA\Property(property="bathrooms", type="integer", example=2),
     *             @OA\Property(property="area", type="integer", example=200),
     *             @OA\Property(property="floor", type="integer", example=2),
     *             @OA\Property(property="address", type="string", example="Jl. Kenangan No. 10"),
     *             @OA\Property(property="parking", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="Available"),
     *             @OA\Property(property="category_type_id", type="integer", example=1),
     *             @OA\Property(property="category_location_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Property created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Property created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Rumah Minimalis")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/property-update/{id}",
     *     tags={"Property"},
     *     summary="Update property",
     *     description="Memperbarui detail properti berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID properti yang ingin diperbarui",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","price","bedrooms","bathrooms","area","floor","address","status","category_type_id","category_location_id"},
     *             @OA\Property(property="title", type="string", example="Rumah Mewah"),
     *             @OA\Property(property="description", type="string", example="Rumah mewah dengan kolam renang"),
     *             @OA\Property(property="price", type="integer", example=2000000000),
     *             @OA\Property(property="bedrooms", type="integer", example=4),
     *             @OA\Property(property="bathrooms", type="integer", example=3),
     *             @OA\Property(property="area", type="integer", example=250),
     *             @OA\Property(property="floor", type="integer", example=2),
     *             @OA\Property(property="address", type="string", example="Jl. Sudirman No. 50"),
     *             @OA\Property(property="parking", type="integer", example=2),
     *             @OA\Property(property="status", type="string", example="Sold"),
     *             @OA\Property(property="category_type_id", type="integer", example=1),
     *             @OA\Property(property="category_location_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Property updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Property updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Rumah Mewah")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Property not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Property not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/property-delete/{id}",
     *     tags={"Property"},
     *     summary="Menghapus properti",
     *     description="Delete Property",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID properti yang ingin dihapus",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Property deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Property deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Property not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Property not found")
     *         )
     *     )
     * )
     */
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
