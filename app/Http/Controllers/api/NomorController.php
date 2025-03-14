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

        $title = 'Rotator Admin';

        return view('admin.rotator', compact('nomors', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nomor' => 'required|numeric',
        ]);
        // dd($request);
        Nomor::create([
            'username' => $request->username,
            'nomor' => $request->nomor,
        ]);

        return redirect()->route('rotator.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $nomor = Nomor::find($id);
        if (!$nomor) {
            return redirect()->back()->with('error', 'User not found');
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'nomor' => 'required|numeric',
        ]);

        $nomor->username = $request->username;
        $nomor->nomor = $request->nomor;

        $nomor->save();

        return redirect()->route('rotator.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $nomor = Nomor::find($id);
        if (!$nomor) {
            return redirect()->back()->with('error', 'User not found');
        }

        $nomor->delete();
        return redirect()->route('rotator.index')->with('success', 'User deleted successfully');
    }

    public function generateLink($id)
    {
        // Text message to be sent
        $text = $this->chatShow($id);

        // Admin WhatsApp numbers from the Nomor table
        $admins = Nomor::pluck('nomor')->toArray();

        // File to store the current admin index
        $indexFile = 'admin_index.txt';

        // Initialize index if file does not exist
        if (!file_exists(storage_path($indexFile))) {
            $currentIndex = 0;
            file_put_contents(storage_path($indexFile), $currentIndex);
        } else {
            $currentIndex = (int)file_get_contents(storage_path($indexFile));
        }

        // Select the next admin
        $adminNumber = $admins[$currentIndex];

        // Update the index for the next user
        $nextIndex = ($currentIndex + 1) % count($admins);
        file_put_contents(storage_path($indexFile), $nextIndex);

        // Generate the WhatsApp URL
        $url = "https://api.whatsapp.com/send?phone=" . $adminNumber . "&text=" . urlencode($text);
        session()->flash('generated_url', $url);

        return $url;
    }

    public function showlink($id = null)
    {
        // Call the generateLink method and get the URL
        $url = $this->generateLink($id);

        // Return the view with the generated URL
        return redirect($url);
    }

    public function chatShow($id = null)
    {
        // Jika $id adalah null atau tidak ditemukan, kembalikan string kosong
        if ($id === null) {
            return "";
        }

        // Coba menemukan property, jika tidak ditemukan kembalikan string kosong
        try {
            $property = Property::findOrFail($id);

            // Format harga dengan pemisah ribuan
            $formattedPrice = number_format($property->price, 0, ',', '.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return "";
        }

        $detailProduct = url('/details-property/' . $property->id);
        
        $text = $detailProduct . PHP_EOL . "🌟 Halo Admin Opendoorz" . PHP_EOL . PHP_EOL
            . "Saya tertarik dengan properti *" . $property->title . "* yang tersedia di website." . PHP_EOL . PHP_EOL
            . "🏡 *Nama Properti*: " . $property->title . PHP_EOL
            . "📍 *Lokasi*: " . $property->address . ", " . ($property->categoryLocation->name ?? '') . PHP_EOL
            . "💰 *Harga*: Rp. " . $formattedPrice . PHP_EOL . PHP_EOL
            . "Saya ingin mengetahui lebih lanjut tentang proses pembelian dan detail lainnya." . PHP_EOL
            . "Bisa tolong dibantu untuk informasinya? Terima kasih! 😊";

        return $text;
    }
}
