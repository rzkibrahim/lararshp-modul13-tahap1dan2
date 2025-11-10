<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KodeTindakanTerapiController extends Controller
{
    public function index()
    {
        // Ambil semua data dengan join ke tabel kategori dan kategori_klinis
        $tindakanTerapi = DB::table('kode_tindakan_terapi')
            ->leftJoin('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
            ->leftJoin('kategori_klinis', 'kode_tindakan_terapi.idkategori_klinis', '=', 'kategori_klinis.idkategori_klinis')
            ->select(
                'kode_tindakan_terapi.idkode_tindakan_terapi',
                'kode_tindakan_terapi.kode',
                'kode_tindakan_terapi.deskripsi_tindakan_terapi',
                'kategori.nama_kategori',
                'kategori_klinis.nama_kategori_klinis'
            )
            ->orderBy('kode_tindakan_terapi.idkode_tindakan_terapi', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.kode-tindakan-terapi.index', compact('tindakanTerapi'));
    }

    public function create()
    {
        $kategori = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
        $kategoriKlinis = DB::table('kategori_klinis')->orderBy('nama_kategori_klinis', 'asc')->get();

        return view('rshp.admin.DataMaster.kode-tindakan-terapi.create', compact('kategori', 'kategoriKlinis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ], [
            'kode.required' => 'Kode wajib diisi',
            'kode.unique' => 'Kode sudah digunakan',
            'deskripsi_tindakan_terapi.required' => 'Deskripsi wajib diisi',
            'idkategori.required' => 'Kategori wajib dipilih',
            'idkategori_klinis.required' => 'Kategori klinis wajib dipilih',
        ]);

        DB::table('kode_tindakan_terapi')->insert([
            'kode' => strtoupper(trim($request->kode)),
            'deskripsi_tindakan_terapi' => ucfirst(trim($request->deskripsi_tindakan_terapi)),
            'idkategori' => $request->idkategori,
            'idkategori_klinis' => $request->idkategori_klinis,
        ]);

        return redirect()->route('admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode Tindakan/Terapi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tindakanTerapi = DB::table('kode_tindakan_terapi')->where('idkode_tindakan_terapi', $id)->first();
        if (!$tindakanTerapi) {
            abort(404, 'Data tindakan terapi tidak ditemukan.');
        }

        $kategori = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
        $kategoriKlinis = DB::table('kategori_klinis')->orderBy('nama_kategori_klinis', 'asc')->get();

        return view('rshp.admin.DataMaster.kode-tindakan-terapi.edit', compact('tindakanTerapi', 'kategori', 'kategoriKlinis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode,' . $id . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        $tindakanTerapi = DB::table('kode_tindakan_terapi')->where('idkode_tindakan_terapi', $id)->first();
        if (!$tindakanTerapi) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('error', 'Data tindakan terapi tidak ditemukan.');
        }

        DB::table('kode_tindakan_terapi')
            ->where('idkode_tindakan_terapi', $id)
            ->update([
                'kode' => strtoupper(trim($request->kode)),
                'deskripsi_tindakan_terapi' => ucfirst(trim($request->deskripsi_tindakan_terapi)),
                'idkategori' => $request->idkategori,
                'idkategori_klinis' => $request->idkategori_klinis,
            ]);

        return redirect()->route('admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode Tindakan/Terapi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $tindakanTerapi = DB::table('kode_tindakan_terapi')->where('idkode_tindakan_terapi', $id)->first();
        if (!$tindakanTerapi) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                ->with('error', 'Data tidak ditemukan.');
        }

        DB::table('kode_tindakan_terapi')->where('idkode_tindakan_terapi', $id)->delete();

        return redirect()->route('admin.kode-tindakan-terapi.index')
            ->with('success', 'Kode Tindakan/Terapi berhasil dihapus.');
    }
}
