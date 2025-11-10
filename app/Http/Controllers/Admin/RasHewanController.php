<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RasHewanController extends Controller
{
    public function index()
    {
        $rasHewan = DB::table('ras_hewan')
            ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select(
                'ras_hewan.idras_hewan',
                'ras_hewan.nama_ras',
                'jenis_hewan.nama_jenis_hewan',
                'ras_hewan.idjenis_hewan'
            )
            ->orderBy('ras_hewan.idras_hewan', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.ras-hewan.index', compact('rasHewan'));
    }

    public function create()
    {
        $jenisHewan = DB::table('jenis_hewan')
            ->orderBy('nama_jenis_hewan', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.ras-hewan.create', compact('jenisHewan'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRasHewan($request);

        DB::table('ras_hewan')->insert([
            'nama_ras' => $this->formatNamaRas($validated['nama_ras']),
            'idjenis_hewan' => $validated['idjenis_hewan'],
        ]);

        return redirect()->route('admin.ras-hewan.index')
            ->with('success', 'Ras hewan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rasHewan = DB::table('ras_hewan')->where('idras_hewan', $id)->first();

        if (!$rasHewan) {
            abort(404, 'Data tidak ditemukan.');
        }

        $jenisHewan = DB::table('jenis_hewan')
            ->orderBy('nama_jenis_hewan', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.ras-hewan.edit', compact('rasHewan', 'jenisHewan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateRasHewan($request, $id);

        DB::table('ras_hewan')
            ->where('idras_hewan', $id)
            ->update([
                'nama_ras' => $this->formatNamaRas($validated['nama_ras']),
                'idjenis_hewan' => $validated['idjenis_hewan'],
            ]);

        return redirect()->route('admin.ras-hewan.index')
            ->with('success', 'Ras hewan berhasil diupdate.');
    }

    public function destroy($id)
    {
        // Cek apakah ras hewan sedang digunakan di tabel pet
        $usedInPet = DB::table('pet')->where('idras_hewan', $id)->count();

        if ($usedInPet > 0) {
            return redirect()->route('admin.ras-hewan.index')
                ->with('error', 'Ras hewan tidak dapat dihapus karena masih digunakan di data pet.');
        }

        $deleted = DB::table('ras_hewan')->where('idras_hewan', $id)->delete();

        if ($deleted) {
            return redirect()->route('admin.ras-hewan.index')
                ->with('success', 'Ras hewan berhasil dihapus.');
        } else {
            return redirect()->route('admin.ras-hewan.index')
                ->with('error', 'Data tidak ditemukan atau gagal dihapus.');
        }
    }

    // ==================== VALIDASI & HELPER ====================

    protected function validateRasHewan(Request $request, $id = null)
    {
        $uniqueRule = $id
            ? 'unique:ras_hewan,nama_ras,' . $id . ',idras_hewan,idjenis_hewan,' . $request->idjenis_hewan
            : 'unique:ras_hewan,nama_ras,NULL,idras_hewan,idjenis_hewan,' . $request->idjenis_hewan;

        return $request->validate([
            'nama_ras' => [
                'required',
                'string',
                'max:100',
                'min:2',
                $uniqueRule,
            ],
            'idjenis_hewan' => [
                'required',
                'exists:jenis_hewan,idjenis_hewan',
            ],
        ], [
            'nama_ras.required' => 'Nama ras wajib diisi.',
            'nama_ras.max' => 'Nama ras maksimal 100 karakter.',
            'nama_ras.min' => 'Nama ras minimal 2 karakter.',
            'nama_ras.unique' => 'Ras ini sudah ada untuk jenis hewan yang dipilih.',
            'idjenis_hewan.required' => 'Jenis hewan wajib dipilih.',
            'idjenis_hewan.exists' => 'Jenis hewan tidak valid.',
        ]);
    }

    protected function formatNamaRas($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}
