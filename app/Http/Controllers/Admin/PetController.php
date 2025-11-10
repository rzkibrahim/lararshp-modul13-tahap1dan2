<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index()
    {
        $pets = DB::table('pet')
            ->leftJoin('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->leftJoin('user', 'pemilik.iduser', '=', 'user.iduser')
            ->leftJoin('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->leftJoin('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select(
                'pet.idpet',
                'pet.nama as nama_pet',
                'pet.jenis_kelamin',
                'pet.tanggal_lahir',
                'pet.warna_tanda',
                'pemilik.idpemilik',
                'user.nama as nama_pemilik',
                'user.email as email_pemilik',
                'ras_hewan.nama_ras',
                'jenis_hewan.nama_jenis_hewan'
            )
            ->orderBy('pet.idpet', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.pet.index', compact('pets'));
    }

    public function create()
    {
        $rasHewan = DB::table('ras_hewan')
            ->leftJoin('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select('ras_hewan.idras_hewan', 'ras_hewan.nama_ras', 'jenis_hewan.nama_jenis_hewan')
            ->orderBy('ras_hewan.nama_ras', 'asc')
            ->get();

        $pemiliks = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select('pemilik.idpemilik', 'user.nama', 'user.email')
            ->get();

        return view('rshp.admin.DataMaster.pet.create', compact('rasHewan', 'pemiliks'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePet($request);

        DB::table('pet')->insert([
            'nama'           => $this->formatNamaPet($validated['nama']),
            'idpemilik'      => $validated['idpemilik'],
            'idras_hewan'    => $validated['idras_hewan'],
            'jenis_kelamin'  => $this->normalizeJK($validated['jenis_kelamin']),
            'tanggal_lahir'  => $validated['tanggal_lahir'] ?? null,
            'warna_tanda'    => $validated['warna_tanda'] ?? null,
        ]);

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pet = DB::table('pet')
            ->leftJoin('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->leftJoin('user', 'pemilik.iduser', '=', 'user.iduser')
            ->leftJoin('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->leftJoin('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select(
                'pet.*',
                'user.nama as nama_pemilik',
                'user.email as email_pemilik',
                'ras_hewan.nama_ras',
                'jenis_hewan.nama_jenis_hewan'
            )
            ->where('pet.idpet', $id)
            ->first();

        if (!$pet) {
            abort(404, 'Data Pet tidak ditemukan.');
        }

        $rasHewan = DB::table('ras_hewan')
            ->leftJoin('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select('ras_hewan.idras_hewan', 'ras_hewan.nama_ras', 'jenis_hewan.nama_jenis_hewan')
            ->orderBy('ras_hewan.nama_ras', 'asc')
            ->get();

        $pemiliks = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select('pemilik.idpemilik', 'user.nama', 'user.email')
            ->get();

        return view('rshp.admin.DataMaster.pet.edit', compact('pet', 'rasHewan', 'pemiliks'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validatePet($request, $id);

        $pet = DB::table('pet')->where('idpet', $id)->first();
        if (!$pet) {
            return redirect()->route('admin.pet.index')->with('error', 'Data Pet tidak ditemukan.');
        }

        DB::table('pet')->where('idpet', $id)->update([
            'nama'           => $this->formatNamaPet($validated['nama']),
            'idpemilik'      => $validated['idpemilik'],
            'idras_hewan'    => $validated['idras_hewan'],
            'jenis_kelamin'  => $this->normalizeJK($validated['jenis_kelamin']),
            'tanggal_lahir'  => $validated['tanggal_lahir'] ?? null,
            'warna_tanda'    => $validated['warna_tanda'] ?? null,
        ]);

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pet = DB::table('pet')->where('idpet', $id)->first();

        if (!$pet) {
            return redirect()->route('admin.pet.index')
                ->with('error', 'Data Pet tidak ditemukan.');
        }

        DB::table('pet')->where('idpet', $id)->delete();

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil dihapus.');
    }

    // ==================== HELPER METHODS ====================

    protected function validatePet(Request $request, $id = null)
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:100', 'min:2'],
            'idpemilik' => ['required', 'exists:pemilik,idpemilik'],
            'idras_hewan' => ['required', 'exists:ras_hewan,idras_hewan'],
            'jenis_kelamin' => ['required', 'in:M,F,Jantan,Betina'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'warna_tanda' => ['nullable', 'string', 'max:45'],
        ], [
            'nama.required' => 'Nama pet wajib diisi.',
            'nama.max' => 'Nama pet maksimal 100 karakter.',
            'nama.min' => 'Nama pet minimal 2 karakter.',
            'idpemilik.required' => 'Pemilik wajib dipilih.',
            'idpemilik.exists' => 'Pemilik tidak valid.',
            'idras_hewan.required' => 'Ras hewan wajib dipilih.',
            'idras_hewan.exists' => 'Ras hewan tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'warna_tanda.max' => 'Warna/tanda maksimal 45 karakter.',
        ]);
    }

    protected function normalizeJK(string $val): string
    {
        $v = strtoupper(trim($val));
        return ($v === 'BETINA' || $v === 'F') ? 'F' : 'M';
    }

    protected function formatNamaPet($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}
