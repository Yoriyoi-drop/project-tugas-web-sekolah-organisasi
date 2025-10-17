<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'MA NU Nusantara'),
            'site_description' => Setting::get('site_description', 'Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman.'),
            'contact_email' => Setting::get('contact_email', 'info@manunusantara.sch.id'),
            'contact_phone' => Setting::get('contact_phone', '+62 123 456 789'),
            'address' => Setting::get('address', 'Jl. Pendidikan No. 123, Jakarta, Indonesia')
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        \Log::info('Settings update request:', $request->all());
        
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'address' => 'required|string'
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('site_description', $request->site_description);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('contact_phone', $request->contact_phone);
        Setting::set('address', $request->address);

        \Log::info('Settings saved. Site name is now: ' . Setting::get('site_name'));

        return redirect()->route('admin.settings.index')->with('success', 'Settings berhasil diperbarui!');
    }
}