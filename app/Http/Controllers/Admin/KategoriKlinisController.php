<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        // Ambil semua data kategori klinis + jumlah tindakan
        $kategoriKlinis = DB::table('kategori_klinis')
            ->leftJoin('kode_tindakan_terapi', 'kategori_klinis.idkategori_klinis', '=', 'kode_tindakan_terapi.idkategori_klinis')
            ->select(
                'kategori_klinis.idkategori_klinis',
                'kategori_klinis.nama_kategori_klinis',
                DB::raw('COUNT(kode_tindakan_terapi.idkategori_klinis) AS jumlah_tindakan')
            )
            ->groupBy('kategori_klinis.idkategori_klinis', 'kategori_klinis.nama_kategori_klinis')
            ->orderBy('kategori_klinis.idkategori_klinis', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.kategori-klinis.index', compact('kategoriKlinis'));
    }

    public function create()
    {
        return view('rshp.admin.DataMaster.kategori-klinis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:50|unique:kategori_klinis,nama_kategori_klinis',
        ], [
            'nama_kategori_klinis.required' => 'Nama kategori klinis wajib diisi',
            'nama_kategori_klinis.unique' => 'Nama kategori klinis sudah ada',
            'nama_kategori_klinis.max' => 'Nama kategori klinis maksimal 50 karakter',
        ]);

        DB::table('kategori_klinis')->insert([
            'nama_kategori_klinis' => trim(ucwords(strtolower($request->nama_kategori_klinis))),
        ]);

        return redirect()->route('admin.kategori-klinis.index')
            ->with('success', 'Kategori Klinis berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategoriKlinis = DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->first();

        if (!$kategoriKlinis) {
            abort(404, 'Data kategori klinis tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.kategori-klinis.edit', compact('kategoriKlinis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:50|unique:kategori_klinis,nama_kategori_klinis,' . $id . ',idkategori_klinis',
        ], [
            'nama_kategori_klinis.required' => 'Nama kategori klinis wajib diisi',
            'nama_kategori_klinis.unique' => 'Nama kategori klinis sudah ada',
            'nama_kategori_klinis.max' => 'Nama kategori klinis maksimal 50 karakter',
        ]);

        $kategoriKlinis = DB::table('kategori_klinis')->where('idkategori_klinis', $id)->first();

        if (!$kategoriKlinis) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Kategori Klinis tidak ditemukan.');
        }

        DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->update([
                'nama_kategori_klinis' => trim(ucwords(strtolower($request->nama_kategori_klinis))),
            ]);

        return redirect()->route('admin.kategori-klinis.index')
            ->with('success', 'Kategori Klinis berhasil diupdate');
    }

    public function destroy($id)
    {
        // Cek apakah masih digunakan di tabel kode_tindakan_terapi
        $digunakan = DB::table('kode_tindakan_terapi')
            ->where('idkategori_klinis', $id)
            ->count();

        if ($digunakan > 0) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Kategori Klinis tidak dapat dihapus karena masih digunakan.');
        }

        DB::table('kategori_klinis')->where('idkategori_klinis', $id)->delete();

        return redirect()->route('admin.kategori-klinis.index')
            ->with('success', 'Kategori Klinis berhasil dihapus.');
    }
}
