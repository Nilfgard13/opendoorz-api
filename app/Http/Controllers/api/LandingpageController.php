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

        $title = 'Home Page';

        return view('user.home', compact('property', 'title', 'propertyCounts', 'landingPage', 'categoryCounts'));
    }

    public function contactIndex(Request $request)
    {
        $landingPage = LandingPage::find(1);

        $title = 'Contact Page';

        return view('user.contact', compact('title', 'landingPage'));
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

        $title = 'Property Page';

        return view('user.property', compact('property', 'title', 'types', 'landingPage'));
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

        return view('user.details-property', compact('property', 'title', 'otherProperties', 'landingPage'));
    }
}
