<?php

namespace App\Http\Controllers;

use App\Models\PPDB;
use App\Http\Requests\PPDBRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PPDBController extends Controller
{
    public function index()
    {
        return view('pages.ppdb.index');
    }

    public function create()
    {
        return view('pages.ppdb.form');
    }

    public function store(PPDBRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Basic sanitization
            $validated['name'] = strip_tags($validated['name']);
            $validated['birth_place'] = strip_tags($validated['birth_place']);
            $validated['address'] = strip_tags($validated['address']);
            $validated['parent_name'] = strip_tags($validated['parent_name']);
            $validated['previous_school'] = strip_tags($validated['previous_school']);
            $validated['desired_major'] = strip_tags($validated['desired_major'] ?? '');
            
            $validated['status'] = 'pending';

            $ppdb = PPDB::create($validated);

            DB::commit();

            return redirect()->route('ppdb.success')
                ->with('registration_name', $ppdb->name);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran Anda. Silakan coba lagi.');
        }
    }

    public function success()
    {
        if (!session('registration_name')) {
            return redirect()->route('ppdb.create');
        }
        return view('pages.ppdb.success');
    }
}
