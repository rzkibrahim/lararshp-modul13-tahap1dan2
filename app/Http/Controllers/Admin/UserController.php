<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('iduser', 'asc')->get();
        return view('rshp.admin.DataMaster.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('nama_role', 'asc')->get();

        return view('rshp.admin.DataMaster.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:3|confirmed',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:role,idrole',
        ]);

        // Simpan user
        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Jika ada role yang dipilih
        if (!empty($validated['role_ids'])) {
            $user->roles()->attach($validated['role_ids']);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = RoleUser::all();

        return view('rshp.admin.DataMaster.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id . ',iduser',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:role,idrole',
        ]);

        $user->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        // Sync role baru (hapus lama dan tambahkan baru)
        $user->roles()->sync($validated['role_ids'] ?? []);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus!');
    }

    // 🔹 Validasi input user
    protected function validateUser(Request $request, $id = null)
    {
        $emailRule = $id
            ? 'unique:user,email,' . $id . ',iduser'
            : 'unique:user,email';

        return $request->validate([
            'nama' => ['required', 'string', 'max:500'],
            'email' => ['required', 'email', 'max:200', $emailRule],
            'password' => $id
                ? 'nullable|string|min:3|confirmed'
                : 'required|string|min:3|confirmed',
        ]);
    }

    protected function createUser(array $data)
    {
        return User::create([
            'nama' => trim($data['nama']),
            'email' => trim($data['email']),
            'password' => Hash::make($data['password']),
        ]);
    }


    // 🔹 Update user
    protected function updateUser(User $user, array $data)
    {
        $updateData = [
            'nama' => $data['nama'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        return $user;
    }
}
