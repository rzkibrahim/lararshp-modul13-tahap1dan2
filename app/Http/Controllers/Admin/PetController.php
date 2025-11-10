<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\RasHewan;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])
            ->orderBy('idpet', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.pet.index', compact('pets'));
    }

    public function create()
    {
        $rasHewan = RasHewan::with('jenisHewan')->orderBy('nama_ras', 'asc')->get();
        $pemiliks = Pemilik::with('user')->get();
        return view('rshp.admin.DataMaster.pet.create', compact('rasHewan', 'pemiliks'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePet($request);
        $pet = $this->createPet($validated);

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pet = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])->findOrFail($id);
        $rasHewan = RasHewan::with('jenisHewan')->orderBy('nama_ras', 'asc')->get();
        $pemiliks = Pemilik::with('user')->get();

        return view('rshp.admin.DataMaster.pet.edit', compact('pet', 'rasHewan', 'pemiliks'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);
        $validated = $this->validatePet($request, $id);
        $this->updatePet($pet, $validated);

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);
        $pet->delete();

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil dihapus.');
    }

    // ==================== HELPER METHODS ====================

    protected function validatePet(Request $request, $id = null)
    {
        return $request->validate([
            'nama' => ['required','string','max:100','min:2'],             // kolom DB: nama (varchar 100)
            'idpemilik' => ['required','exists:pemilik,idpemilik'],
            'idras_hewan' => ['required','exists:ras_hewan,idras_hewan'],
            // terima M/F atau Jantan/Betina, nanti dinormalisasi ke M/F
            'jenis_kelamin' => ['required','in:M,F,Jantan,Betina'],
            'tanggal_lahir' => ['nullable','date','before_or_equal:today'],
            'warna_tanda' => ['nullable','string','max:45'],               // kolom DB: warna_tanda (varchar 45)
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

    protected function createPet(array $data)
    {
        return Pet::create([
            'nama'           => $this->formatNamaPet($data['nama']),
            'idpemilik'      => $data['idpemilik'],
            'idras_hewan'    => $data['idras_hewan'],
            'jenis_kelamin'  => $this->normalizeJK($data['jenis_kelamin']),
            'tanggal_lahir'  => $data['tanggal_lahir'] ?? null,
            'warna_tanda'    => $data['warna_tanda'] ?? null,
        ]);
    }

    protected function updatePet(Pet $pet, array $data)
    {
        $pet->update([
            'nama'           => $this->formatNamaPet($data['nama']),
            'idpemilik'      => $data['idpemilik'],
            'idras_hewan'    => $data['idras_hewan'],
            'jenis_kelamin'  => $this->normalizeJK($data['jenis_kelamin']),
            'tanggal_lahir'  => $data['tanggal_lahir'] ?? null,
            'warna_tanda'    => $data['warna_tanda'] ?? null,
        ]);

        return $pet;
    }

    protected function normalizeJK(string $val): string
    {
        // Terima "Jantan"/"Betina" atau "M"/"F" → simpan 'M' atau 'F' sesuai isi DB
        $v = strtoupper(trim($val));
        if ($v === 'BETINA' || $v === 'F') return 'F';
        return 'M';
    }

    protected function formatNamaPet($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}
