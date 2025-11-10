<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        // Ambil semua kategori + hitung jumlah data di kode_tindakan_terapi (LEFT JOIN agar yang 0 tetap muncul)
        $kategori = DB::table('kategori')
            ->leftJoin('kode_tindakan_terapi', 'kategori.idkategori', '=', 'kode_tindakan_terapi.idkategori')
            ->select(
                'kategori.idkategori',
                'kategori.nama_kategori',
                DB::raw('COUNT(kode_tindakan_terapi.idkode_tindakan_terapi) AS jumlah_tindakan')
            )
            ->groupBy('kategori.idkategori', 'kategori.nama_kategori')
            ->orderBy('kategori.idkategori', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.kategori.index', compact('kategori'));
    }


    public function create()
    {
        return view('rshp.admin.DataMaster.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
        ]);

        DB::table('kategori')->insert([
            'nama_kategori' => trim(ucwords(strtolower($request->nama_kategori))),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = DB::table('kategori')->where('idkategori', $id)->first();

        if (!$kategori) {
            abort(404, 'Data kategori tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id . ',idkategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
        ]);

        $kategori = DB::table('kategori')->where('idkategori', $id)->first();

        if (!$kategori) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak ditemukan');
        }

        DB::table('kategori')
            ->where('idkategori', $id)
            ->update([
                'nama_kategori' => trim(ucwords(strtolower($request->nama_kategori))),
            ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }


    public function destroy($id)
    {
        // Periksa apakah kategori masih digunakan di tabel lain
        $digunakan = DB::table('kode_tindakan_terapi')
            ->where('idkategori', $id)
            ->count();

        if ($digunakan > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan.');
        }

        DB::table('kategori')->where('idkategori', $id)->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
