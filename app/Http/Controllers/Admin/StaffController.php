<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffUser;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        $staffUsers = StaffUser::with('roles')->get();
        return view('admin.staff.index', compact('staffUsers'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:staff_users,username',
            'email'    => 'required|string|email|unique:staff_users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string',
        ]);

        $staff = StaffUser::create([
            'name'      => $data['name'],
            'username'  => $data['username'] ?? null,
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'is_active' => true,
        ]);

        $staff->assignRole($data['role']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $staff = StaffUser::findOrFail($id);
        $roles = Role::all();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $staff = StaffUser::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:staff_users,username,' . $id,
            'email'    => 'required|string|email|unique:staff_users,email,' . $id,
            'role'     => 'required|string',
        ]);

        $staff->update([
            'name'     => $data['name'],
            'username' => $data['username'] ?? null,
            'email'    => $data['email'],
        ]);

        $staff->syncRoles([$data['role']]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $staff = StaffUser::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus.');
    }

    public function toggle($id)
    {
        $staff = StaffUser::findOrFail($id);

        $staff->is_active = !(bool) $staff->is_active;
        $staff->save();

        return back()->with('success', 'Status staff diperbarui.');
    }

    public function resetPassword($id)
    {
        $staff = StaffUser::findOrFail($id);

        $newPassword = Str::random(10);

        $staff->password = Hash::make($newPassword);
        $staff->save();

        return back()->with('success', 'Password baru untuk ' . $staff->name . ': ' . $newPassword);
    }
}
