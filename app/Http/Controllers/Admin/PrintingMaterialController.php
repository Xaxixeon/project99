<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintingMaterial;
use Illuminate\Http\Request;

class PrintingMaterialController extends Controller
{
    public function index()
    {
        $materials = PrintingMaterial::orderBy('name')->get();
        return view('admin.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('admin.materials.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50',
            'price_per_m2' => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        PrintingMaterial::create($data);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    public function edit(PrintingMaterial $material)
    {
        return view('admin.materials.edit', compact('material'));
    }

    public function update(Request $request, PrintingMaterial $material)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50',
            'price_per_m2' => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $material->update($data);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(PrintingMaterial $material)
    {
        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material berhasil dihapus.');
    }
}
