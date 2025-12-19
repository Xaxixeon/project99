<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberType;
use Illuminate\Http\Request;

class MemberTypeController extends Controller
{
    public function index()
    {
        return view('admin.member.index', [
            'types' => MemberType::paginate(20)
        ]);
    }


    public function create()
    {
        return view('admin.member.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|unique:member_types,code',
            'name'             => 'required|string',
            'label'            => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'description'      => 'nullable|string',
            'default_discount' => 'nullable|numeric',
        ]);

        MemberType::create($data);

        return redirect()->route('admin.member.index')->with('success', 'Member type ditambahkan.');
    }


    public function edit(MemberType $member)
    {
        return view('admin.member.edit', [
            'memberType' => $member
        ]);
    }


    public function update(Request $request, MemberType $member)
    {
        $data = $request->validate([
            'code'             => "required|string|unique:member_types,code,$member->id",
            'name'             => 'required|string',
            'label'            => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'description'      => 'nullable|string',
            'default_discount' => 'nullable|numeric',
        ]);

        $member->update($data);

        return back()->with('success', 'Member type diperbarui.');
    }


    public function destroy(MemberType $member)
    {
        $member->delete();

        return back()->with('success', 'Member type dihapus.');
    }
}
