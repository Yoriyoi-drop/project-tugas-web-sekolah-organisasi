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
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'phone' => strip_tags($request->phone),
            'class' => strip_tags($request->class),
            'nis' => strip_tags($request->nis),
            'address' => strip_tags($request->address),
            'motivation' => strip_tags($request->motivation),
            'skills' => $skills,
            'experiences' => $experiences
        ]);

        return redirect()->route('organisasi')->with('success', 'Pendaftaran berhasil dikirim! Kami akan menghubungi Anda segera.');
    }
}
