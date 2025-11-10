<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisHewanController extends Controller
{
    public function index()
    {
        // Ambil data jenis hewan + hitung jumlah ras
        $jenisHewan = DB::table('jenis_hewan')
            ->leftJoin('ras_hewan', 'jenis_hewan.idjenis_hewan', '=', 'ras_hewan.idjenis_hewan')
            ->select(
                'jenis_hewan.idjenis_hewan',
                'jenis_hewan.nama_jenis_hewan',
                DB::raw('COUNT(ras_hewan.idras_hewan) as jumlah_ras')
            )
            ->groupBy('jenis_hewan.idjenis_hewan', 'jenis_hewan.nama_jenis_hewan')
            ->orderBy('jenis_hewan.idjenis_hewan', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.jenis-hewan.index', compact('jenisHewan'));
    }


    public function create()
    {
        return view('rshp.admin.DataMaster.jenis-hewan.create');
    }

    public function store(Request $request)
    {
        $validateData = $this->validateJenisHewan($request);

        $this->createJenisHewan($validateData);

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', 'Jenis hewan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenisHewan = DB::table('jenis_hewan')->where('idjenis_hewan', $id)->first();

        if (!$jenisHewan) {
            abort(404, 'Data tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.jenis-hewan.edit', compact('jenisHewan'));
    }

    public function update(Request $request, $id)
    {
        $this->validateJenisHewan($request, $id);

        DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->update([
                'nama_jenis_hewan' => $this->formatJenisHewanName($request->nama_jenis_hewan),
            ]);

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', 'Jenis hewan berhasil diupdate.');
    }

    public function destroy($id)
    {
        DB::table('jenis_hewan')->where('idjenis_hewan', $id)->delete();

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', 'Jenis hewan berhasil dihapus.');
    }

    // 🔹 Validasi input data
    public function validateJenisHewan(Request $request, $id = null)
    {
        $uniqueRule = $id
            ? 'unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan'
            : 'unique:jenis_hewan,nama_jenis_hewan';

        return $request->validate([
            'nama_jenis_hewan' => ['required', 'string', 'max:255', 'min:3', $uniqueRule],
        ], [
            'nama_jenis_hewan.required' => 'Nama jenis hewan wajib diisi.',
            'nama_jenis_hewan.unique' => 'Nama jenis hewan sudah ada.',
            'nama_jenis_hewan.max' => 'Nama jenis hewan maksimal 255 karakter.',
            'nama_jenis_hewan.min' => 'Nama jenis hewan minimal 3 karakter.',
            'nama_jenis_hewan.string' => 'Nama jenis hewan harus berupa teks.',
        ]);
    }

    // 🔹 Tambah data ke tabel jenis_hewan
    public function createJenisHewan(array $data)
    {
        try {
            return DB::table('jenis_hewan')->insert([
                'nama_jenis_hewan' => $this->formatJenisHewanName($data['nama_jenis_hewan']),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating Jenis Hewan: ' . $e->getMessage());
            throw new \Exception('Gagal menambahkan jenis hewan.');
        }
    }

    // 🔹 Format nama (ucwords + trim)
    public function formatJenisHewanName($name)
    {
        return trim(ucwords(strtolower($name)));
    }
}
