<?php

namespace App\Http\Controllers\api;

use App\Models\Nomor;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NomorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $nomors = Nomor::when($search, function ($query, $search) {
            return $query->where('username', 'LIKE', "%{$search}%")
                ->orWhere('nomor', 'LIKE', "%{$search}%");
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $nomors
        ]);
    }

    public function store(Request $request)
    {
        try {
            $nomors = $request->validate([
                'username' => 'required|string|max:255',
                'nomor' => 'required|numeric',
            ]);
            // dd($request);
            Nomor::create([
                'username' => $request->username,
                'nomor' => $request->nomor,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $nomors
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $nomor = Nomor::find($id);
            if (!$nomor) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $request->validate([
                'username' => 'required|string|max:255',
                'nomor' => 'required|numeric',
            ]);

            $nomor->username = $request->username;
            $nomor->nomor = $request->nomor;

            $nomor->save();

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $nomor
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
        $nomor = Nomor::find($id);
        if (!$nomor) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $nomor->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function generateLink($id)
    {
        $text = $this->chatShow($id);

        $admins = Nomor::pluck('nomor')->toArray();

        if (empty($admins)) {
            return response()->json([
                'success' => false,
                'message' => 'No admin numbers available'
            ], 400);
        }

        $indexFile = 'admin_index.txt';

        if (!file_exists(storage_path($indexFile))) {
            $currentIndex = 0;
            file_put_contents(storage_path($indexFile), $currentIndex);
        } else {
            $currentIndex = (int)file_get_contents(storage_path($indexFile));
        }

        $adminNumber = $admins[$currentIndex];

        $nextIndex = ($currentIndex + 1) % count($admins);
        file_put_contents(storage_path($indexFile), $nextIndex);

        $url = "https://api.whatsapp.com/send?phone=" . $adminNumber . "&text=" . urlencode($text);
        // session()->flash('generated_url', $url);

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp link generated successfully',
            'data' => ['url' => $url]
        ]);
    }

    public function showlink($id = null)
    {
        return $this->generateLink($id);
    }

    public function chatShow($id = null)
    {
        if ($id === null) {
            return response()->json([
                'success' => false,
                'message' => 'Property ID is required'
            ], 400);
        }

        try {
            $property = Property::findOrFail($id);

            $formattedPrice = number_format($property->price, 0, ',', '.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $detailProduct = url('/details-property/' . $property->id);

        $text = $detailProduct . PHP_EOL . "🌟 Halo Admin Opendoorz" . PHP_EOL . PHP_EOL
            . "Saya tertarik dengan properti *" . $property->title . "* yang tersedia di website." . PHP_EOL . PHP_EOL
            . "🏡 *Nama Properti*: " . $property->title . PHP_EOL
            . "📍 *Lokasi*: " . $property->address . ", " . ($property->categoryLocation->name ?? '') . PHP_EOL
            . "💰 *Harga*: Rp. " . $formattedPrice . PHP_EOL . PHP_EOL
            . "Saya ingin mengetahui lebih lanjut tentang proses pembelian dan detail lainnya." . PHP_EOL
            . "Bisa tolong dibantu untuk informasinya? Terima kasih! 😊";

        return response()->json([
            'success' => true,
            'message' => 'Chat message generated successfully',
            'data' => ['message' => $text]
        ]);
    }
}
