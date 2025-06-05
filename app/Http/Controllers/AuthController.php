<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // regsiter
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // cek email apakan sudah terdaftar
        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email already registered'], 409);
        }
        try {
            // Buat pengguna baru
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            // Buat token untuk autentikasi
            $token = $user->createToken('auth_token')->plainTextToken;

            // // Return response sukses
            return response()->json([
                'message' => 'Registrasi berhasil',
                'user' => $user,
                'token' => $token
            ], 201); // 201 Created

            // return view('team');
        } catch (\Exception $e) {

            // Tangani kesalahan jika ada
            return response()->json([
                'message' => 'Registrasi gagal',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    // login
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'message' => 'Email or password is incorrect'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

       

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }
}
