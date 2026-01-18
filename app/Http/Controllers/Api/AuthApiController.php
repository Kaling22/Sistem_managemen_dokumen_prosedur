<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthApiController extends Controller
{
    // 🔹 Login
    public function login(Request $request)
    {
        $request->validate([
            'nrp' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nrp', $request->nrp)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Buat token sanctum
        $token = $user->createToken('flutter_app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'nrp' => $user->nrp,
                'departemen' => $user->departemen,
                'role' => $user->role,
            ]
        ]);
    }

    // 🔹 Ambil data user login
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // 🔹 Logout (hapus token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
