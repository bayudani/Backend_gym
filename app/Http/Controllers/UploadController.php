<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Ambil nama asli file yang dikirim dari Express
            $fileName = $file->getClientOriginalName();
            
            // Simpan dengan nama asli menggunakan storeAs()
            $path = $file->storeAs('bukti-transfer', $fileName, 'public');
            
            return response()->json(['path' => $path], 200);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
