<?php

namespace App\Http\Controllers\api;

use App\Models\Property;
use App\Models\LandingPage;
use App\Models\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingpageController extends Controller
{
    public function homeIndex(Request $request)
    {
        $propertyCounts = [
            'sold' => Property::where('status', 'sold')->count(),
            'total' => Property::count()
        ];
        $categoryCounts = CategoryType::all()->count();
        $property = Property::where('status', 'available')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $landingPage = LandingPage::find(1);

        return response()->json([
            'success' => true,
            'message' => 'Home data retrieved successfully',
            'data' => [
                'property' => $property,
                'property_counts' => $propertyCounts,
                'category_counts' => $categoryCounts,
                'landing_page' => $landingPage,
            ]
        ]);
    }

    public function contactIndex(Request $request)
    {
        $landingPage = LandingPage::find(1);

        return response()->json([
            'success' => true,
            'message' => 'Contact page data retrieved successfully',
            'data' => [
                'landing_page' => $landingPage,
            ]
        ]);
    }

    public function propertyIndex(Request $request)
    {
        $search = $request->input('search');
        $landingPage = LandingPage::find(1);
        $types = CategoryType::all();

        $property = Property::where('status', 'available')
            ->when($search, function ($query, $search) {
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
            })->orderBy('id', 'desc')->paginate(9);

        return response()->json([
            'success' => true,
            'message' => 'Property data retrieved successfully',
            'data' => [
                'property' => $property,
                'types' => $types,
                'landing_page' => $landingPage,
            ]
        ]);
    }

    public function detailsIndex($id)
    {
        $property = Property::where('status', 'available')->findOrFail($id);
        $landingPage = LandingPage::find(1);
        $otherProperties = Property::where('id', '!=', $property->id)
            ->where('status', 'available')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $title = 'Detail Page';

        return response()->json([
            'success' => true,
            'message' => 'Property details retrieved successfully',
            'data' => [
                'property' => $property,
                'other_properties' => $otherProperties,
                'landing_page' => $landingPage,
            ]
        ]);
    }
}
