<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index()
    {
        return view('admin.instansi.index', [
            'instansi' => Instansi::paginate(20)
        ]);
    }


    public function create()
    {
        return view('admin.instansi.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'contact' => 'nullable',
            'address' => 'nullable',
        ]);

        Instansi::create($request->all());

        return redirect()->route('admin.instansi.index')->with('success', 'Instansi ditambahkan.');
    }


    public function edit(Instansi $instansi)
    {
        return view('admin.instansi.edit', compact('instansi'));
    }


    public function update(Request $request, Instansi $instansi)
    {
        $request->validate([
            'name'    => 'required',
            'contact' => 'nullable',
            'address' => 'nullable',
        ]);

        $instansi->update($request->all());

        return back()->with('success', 'Instansi diperbarui.');
    }


    public function destroy(Instansi $instansi)
    {
        $instansi->delete();

        return back()->with('success', 'Instansi dihapus.');
    }
}
