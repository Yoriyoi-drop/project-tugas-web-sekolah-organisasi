<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        $statistics = Statistic::select('id', 'label', 'value', 'description')
                               ->latest()->paginate(10);
        return view('admin.statistics.index', compact('statistics'));
    }

    public function create()
    {
        return view('admin.statistics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|max:255',
            'value' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        $data = $request->only(['label', 'value', 'description', 'order']);
        $data['label'] = strip_tags($data['label']);
        $data['value'] = strip_tags($data['value']);
        $data['description'] = strip_tags($data['description']);

        Statistic::create($data);
        return redirect()->route('admin.statistics.index')->with('success', 'Statistic created successfully');
    }

    public function show(Statistic $statistic)
    {
        return view('admin.statistics.show', compact('statistic'));
    }

    public function edit(Statistic $statistic)
    {
        return view('admin.statistics.edit', compact('statistic'));
    }

    public function update(Request $request, Statistic $statistic)
    {
        $request->validate([
            'label' => 'required|max:255',
            'value' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        $data = $request->only(['label', 'value', 'description', 'order']);
        $data['label'] = strip_tags($data['label']);
        $data['value'] = strip_tags($data['value']);
        $data['description'] = strip_tags($data['description']);

        $statistic->update($data);
        return redirect()->route('admin.statistics.index')->with('success', 'Statistic updated successfully');
    }

    public function destroy(Statistic $statistic)
    {
        $statistic->delete();
        return redirect()->route('admin.statistics.index')->with('success', 'Statistic deleted successfully');
    }
}