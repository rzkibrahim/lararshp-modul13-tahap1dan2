<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::table('role')
            ->leftJoin('role_user', 'role.idrole', '=', 'role_user.idrole')
            ->leftJoin('user', 'role_user.iduser', '=', 'user.iduser')
            ->select(
                'role.idrole',
                'role.nama_role',
                DB::raw('COUNT(role_user.iduser) as jumlah_user')
            )
            ->groupBy('role.idrole', 'role.nama_role')
            ->orderBy('role.idrole', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.role.index', compact('roles'));
    }

    public function create()
    {
        return view('rshp.admin.DataMaster.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role'
        ], [
            'nama_role.required' => 'Nama role wajib diisi',
            'nama_role.unique' => 'Nama role sudah ada',
            'nama_role.max' => 'Nama role maksimal 100 karakter'
        ]);

        DB::table('role')->insert([
            'nama_role' => trim(ucwords(strtolower($request->nama_role)))
        ]);

        return redirect()->route('admin.role.index')
            ->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = DB::table('role')->where('idrole', $id)->first();

        if (!$role) {
            abort(404, 'Role tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role,' . $id . ',idrole'
        ], [
            'nama_role.required' => 'Nama role wajib diisi',
            'nama_role.unique' => 'Nama role sudah ada',
            'nama_role.max' => 'Nama role maksimal 100 karakter'
        ]);

        DB::table('role')->where('idrole', $id)->update([
            'nama_role' => trim(ucwords(strtolower($request->nama_role)))
        ]);

        return redirect()->route('admin.role.index')
            ->with('success', 'Role berhasil diupdate');
    }

    public function destroy($id)
    {
        // Ganti juga di sini
        $usedByUser = DB::table('user')->where('role_id', $id)->count();

        if ($usedByUser > 0) {
            return redirect()->route('admin.role.index')
                ->with('error', 'Role tidak dapat dihapus karena sedang digunakan oleh user.');
        }

        $deleted = DB::table('role')->where('idrole', $id)->delete();

        if ($deleted) {
            return redirect()->route('admin.role.index')
                ->with('success', 'Role berhasil dihapus');
        }

        return redirect()->route('admin.role.index')
            ->with('error', 'Role tidak ditemukan atau gagal dihapus.');
    }
}
