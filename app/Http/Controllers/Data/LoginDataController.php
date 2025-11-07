<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginDataController extends Controller
{
    /**
     * Return authenticated user data (login info).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Tailored payload for API clients (token-based)
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => (bool) $user->is_admin,
            'avatar' => $user->avatar ?? null,
            'phone' => $user->getSecurePhoneAttribute(),
        ]);
    }
}
