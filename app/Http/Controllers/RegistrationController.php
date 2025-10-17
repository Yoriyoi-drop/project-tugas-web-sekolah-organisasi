<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function show(Organization $organization)
    {
        return view('registration.form', compact('organization'));
    }

    public function store(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'class' => 'required|string|max:10',
            'nis' => 'required|string|max:20',
            'address' => 'required|string',
            'motivation' => 'required|string|min:50',
            'skills' => 'nullable|string',
            'experiences' => 'nullable|string'
        ]);

        $skills = $request->skills ? array_filter(array_map('trim', explode(',', $request->skills))) : [];
        $experiences = $request->experiences ? array_filter(array_map('trim', explode(',', $request->experiences))) : [];

        Registration::create([
            'organization_id' => $organization->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'class' => $request->class,
            'nis' => $request->nis,
            'address' => $request->address,
            'motivation' => $request->motivation,
            'skills' => $skills,
            'experiences' => $experiences
        ]);

        return redirect()->route('organisasi')->with('success', 'Pendaftaran berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
