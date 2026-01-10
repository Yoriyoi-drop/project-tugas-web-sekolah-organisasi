<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PPDB;
use App\Http\Requests\PPDBRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PPDBController extends Controller
{
    public function index()
    {
        $ppdb = PPDB::latest()->paginate(10);
        return view('admin.ppdb.index', compact('ppdb'));
    }

    public function create()
    {
        return view('admin.ppdb.create');
    }

    public function store(PPDBRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $validated['name'] = strip_tags($validated['name']);
            $validated['birth_place'] = strip_tags($validated['birth_place']);
            $validated['address'] = strip_tags($validated['address']);
            $validated['parent_name'] = strip_tags($validated['parent_name']);
            $validated['previous_school'] = strip_tags($validated['previous_school']);
            $validated['desired_major'] = strip_tags($validated['desired_major'] ?? '');

            $ppdb = PPDB::create($validated);

            DB::commit();

            return redirect()->route('admin.ppdb.index')
                ->with('success', 'Pendaftaran PPDB berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(PPDB $ppdb)
    {
        return view('admin.ppdb.show', compact('ppdb'));
    }

    public function edit(PPDB $ppdb)
    {
        return view('admin.ppdb.edit', compact('ppdb'));
    }

    public function update(PPDBRequest $request, PPDB $ppdb)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $validated['name'] = strip_tags($validated['name']);
            $validated['birth_place'] = strip_tags($validated['birth_place']);
            $validated['address'] = strip_tags($validated['address']);
            $validated['parent_name'] = strip_tags($validated['parent_name']);
            $validated['previous_school'] = strip_tags($validated['previous_school']);
            $validated['desired_major'] = strip_tags($validated['desired_major'] ?? '');

            $ppdb->update($validated);

            DB::commit();

            return redirect()->route('admin.ppdb.index')
                ->with('success', 'Data PPDB berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(PPDB $ppdb)
    {
        try {
            $ppdb->delete();

            return redirect()->route('admin.ppdb.index')
                ->with('success', 'Data PPDB berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
