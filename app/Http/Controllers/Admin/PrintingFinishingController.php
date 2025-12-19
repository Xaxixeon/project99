<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintingFinishing;
use Illuminate\Http\Request;

class PrintingFinishingController extends Controller
{
    public function index()
    {
        $finishings = PrintingFinishing::orderBy('name')->get();
        return view('admin.finishings.index', compact('finishings'));
    }

    public function create()
    {
        return view('admin.finishings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50',
            'price_per_m2' => 'required|integer|min:0',
            'flat_fee'     => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        PrintingFinishing::create($data);

        return redirect()->route('admin.finishings.index')
            ->with('success', 'Finishing berhasil ditambahkan.');
    }

    public function edit(PrintingFinishing $finishing)
    {
        return view('admin.finishings.edit', compact('finishing'));
    }

    public function update(Request $request, PrintingFinishing $finishing)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50',
            'price_per_m2' => 'required|integer|min:0',
            'flat_fee'     => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $finishing->update($data);

        return redirect()->route('admin.finishings.index')
            ->with('success', 'Finishing berhasil diperbarui.');
    }

    public function destroy(PrintingFinishing $finishing)
    {
        $finishing->delete();

        return redirect()->route('admin.finishings.index')
            ->with('success', 'Finishing berhasil dihapus.');
    }
}
