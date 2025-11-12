<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SecurityService;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        
        // Delete old avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        try {
            $path = $request->file('avatar')->store('avatars', 'public');
            
            if (!$path) {
                return redirect()->route('profile.show')->with('error', 'Gagal mengupload avatar. Silakan coba lagi.');
            }

            $user->update(['avatar' => $path]);
            
            SecurityService::logActivity('avatar_uploaded', ['path' => $path]);
            
            return redirect()->route('profile.show')->with('success', 'Avatar berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Avatar upload failed: ' . $e->getMessage());
            return redirect()->route('profile.show')->with('error', 'Gagal mengupload avatar. Silakan coba lagi.');
        }
    }

    public function delete()
    {
        $user = Auth::user();
        
        if ($user->avatar) {
            try {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
                SecurityService::logActivity('avatar_deleted');
            } catch (\Exception $e) {
                \Log::error('Avatar delete failed: ' . $e->getMessage());
                return redirect()->route('profile.show')->with('error', 'Gagal menghapus avatar. Silakan coba lagi.');
            }
        }
        
        return redirect()->route('profile.show')->with('success', 'Avatar berhasil dihapus.');
    }
}