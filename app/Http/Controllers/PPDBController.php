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
        $ppdb = PPDB::latest()->paginate(10);
        return view('ppdb.index', compact('ppdb'));
    }

    public function create()
    {
        return view('ppdb.create');
    }

    public function store(PPDBRequest $request)
    {
        try {
            DB::beginTransaction();

            $ppdb = PPDB::create($request->validated());

            DB::commit();

            return redirect()->route('ppdb.index')
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
        return view('ppdb.show', compact('ppdb'));
    }

    public function edit(PPDB $ppdb)
    {
        return view('ppdb.edit', compact('ppdb'));
    }

    public function update(PPDBRequest $request, PPDB $ppdb)
    {
        try {
            DB::beginTransaction();

            $ppdb->update($request->validated());

            DB::commit();

            return redirect()->route('ppdb.index')
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

            return redirect()->route('ppdb.index')
                ->with('success', 'Data PPDB berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
