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
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        $user = Auth::user();
        
        // Hapus avatar lama
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Simpan avatar baru
        try {
            $file = $request->file('avatar');
            \Log::info('Avatar upload attempt', [
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName()
            ]);
            
            $path = $file->store('avatars', 'public');
            
            if (!$path) {
                \Log::error('Avatar upload failed: Unable to store file');
                return redirect()->route('profile.show')->with('error', 'Gagal mengupload avatar. Silakan coba lagi.');
            }

            $user->update(['avatar' => $path]);
            
            SecurityService::logActivity('avatar_uploaded', ['path' => $path]);
            
            return redirect()->route('profile.show')->with('success', 'Avatar berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Avatar upload failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
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