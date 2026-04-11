<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Value;
use Illuminate\Http\Request;

class ValueController extends Controller
{
    public function index()
    {
        $values = Value::ordered()->paginate(15);
        return view('admin.values.index', compact('values'));
    }

    public function create()
    {
        return view('admin.values.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['title'] = strip_tags($validated['title']);
        $validated['description'] = strip_tags($validated['description']);
        $validated['is_active'] = $request->boolean('is_active', true);

        if (!$request->filled('order')) {
            $validated['order'] = Value::max('order') + 1;
        }

        Value::create($validated);

        return redirect()->route('admin.values.index')
            ->with('success', 'Value berhasil ditambahkan.');
    }

    public function show(Value $value)
    {
        return view('admin.values.show', compact('value'));
    }

    public function edit(Value $value)
    {
        return view('admin.values.edit', compact('value'));
    }

    public function update(Request $request, Value $value)
    {
        $validated = $request->validate([
            'icon' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['title'] = strip_tags($validated['title']);
        $validated['description'] = strip_tags($validated['description']);
        $validated['is_active'] = $request->boolean('is_active');

        $value->update($validated);

        return redirect()->route('admin.values.index')
            ->with('success', 'Value berhasil diperbarui.');
    }

    public function destroy(Value $value)
    {
        $value->delete();

        return redirect()->route('admin.values.index')
            ->with('success', 'Value berhasil dihapus.');
    }
}
