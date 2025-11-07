<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\PPDB;

class PpdbDataController extends Controller
{
    /**
     * Return list of PPDB entries
     */
    public function index()
    {
        $items = PPDB::orderBy('created_at', 'desc')->get();
        return response()->json($items);
    }

    /**
     * Show a single PPDB entry
     */
    public function show($id)
    {
        $item = PPDB::find($id);
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }
}
