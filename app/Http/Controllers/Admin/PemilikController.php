<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik::with('user')
            ->orderBy('idpemilik', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('pemilik')->get();
        return view('rshp.admin.DataMaster.pemilik.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePemilik($request);

        Pemilik::create([
            'iduser' => $validated['iduser'],
            'alamat' => $validated['alamat'],
            'no_wa' => $this->formatNoWa($validated['no_wa']),
        ]);


        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemilik = Pemilik::with('user')->findOrFail($id);
        $users = User::whereDoesntHave('pemilik')
            ->orWhere('iduser', $pemilik->iduser)
            ->get();

        return view('rshp.admin.DataMaster.pemilik.edit', compact('pemilik', 'users'));
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);
        $validated = $this->validatePemilik($request, $id);

        $pemilik->update([
            'iduser' => $validated['iduser'],
            'alamat' => $validated['alamat'],
            'no_wa' => $this->formatNoWa($validated['no_wa']),
        ]);

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pemilik = Pemilik::findOrFail($id);

        if ($pemilik->pets()->count() > 0) {
            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Pemilik tidak dapat dihapus karena masih memiliki pet.');
        }

        $pemilik->delete();

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil dihapus.');
    }

    // ==================== VALIDASI & HELPER ====================

    protected function validatePemilik(Request $request, $id = null)
    {
        $userUniqueRule = $id ?
            'unique:pemilik,iduser,' . $id . ',idpemilik' :
            'unique:pemilik,iduser';

        return $request->validate([
            'iduser' => [
                'required',
                'exists:user,iduser',
                $userUniqueRule
            ],
            'alamat' => [
                'required',
                'string',
                'max:1000',
                'min:10'
            ],
            'no_wa' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9+\-\s()]*$/'
            ],
        ], [
            'iduser.required' => 'User wajib dipilih.',
            'iduser.exists' => 'User tidak valid.',
            'iduser.unique' => 'User ini sudah terdaftar sebagai pemilik.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.max' => 'Nomor WhatsApp maksimal 15 karakter.',
            'no_wa.regex' => 'Format nomor WhatsApp tidak valid.',
        ]);
    }


    protected function formatNama($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }

    protected function formatNoWa($no)
    {
        $no = preg_replace('/[^0-9]/', '', $no);
        if (substr($no, 0, 1) === '0') {
            $no = '62' . substr($no, 1);
        }
        return $no;
    }
}
