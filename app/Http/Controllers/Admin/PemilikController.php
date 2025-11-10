<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemilikController extends Controller
{
    public function index()
    {
        $pemiliks = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select(
                'pemilik.idpemilik',
                'pemilik.iduser',
                'user.nama',
                'user.email',
                'pemilik.alamat',
                'pemilik.no_wa'
            )
            ->orderBy('pemilik.idpemilik', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        // Ambil user yang belum punya pemilik
        $users = DB::table('user')
            ->whereNotIn('iduser', function ($query) {
                $query->select('iduser')->from('pemilik');
            })
            ->get();

        return view('rshp.admin.DataMaster.pemilik.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePemilik($request);

        DB::table('pemilik')->insert([
            'iduser' => $validated['iduser'],
            'alamat' => $validated['alamat'],
            'no_wa'  => $this->formatNoWa($validated['no_wa']),
        ]);

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemilik = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select(
                'pemilik.idpemilik',
                'pemilik.iduser',
                'user.nama',
                'user.email',
                'pemilik.alamat',
                'pemilik.no_wa'
            )
            ->where('pemilik.idpemilik', $id)
            ->first();

        if (!$pemilik) {
            abort(404, 'Data pemilik tidak ditemukan.');
        }

        // Ambil user yang belum punya pemilik atau user saat ini
        $users = DB::table('user')
            ->whereNotIn('iduser', function ($query) {
                $query->select('iduser')->from('pemilik');
            })
            ->orWhere('iduser', $pemilik->iduser)
            ->get();

        return view('rshp.admin.DataMaster.pemilik.edit', compact('pemilik', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validatePemilik($request, $id);

        $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();

        if (!$pemilik) {
            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Pemilik tidak ditemukan.');
        }

        DB::table('pemilik')
            ->where('idpemilik', $id)
            ->update([
                'iduser' => $validated['iduser'],
                'alamat' => $validated['alamat'],
                'no_wa'  => $this->formatNoWa($validated['no_wa']),
            ]);

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();

        if (!$pemilik) {
            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Pemilik tidak ditemukan.');
        }

        // Cek apakah pemilik masih punya pet
        $jumlahPet = DB::table('pet')
            ->where('idpemilik', $id)
            ->count();

        if ($jumlahPet > 0) {
            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Pemilik tidak dapat dihapus karena masih memiliki pet.');
        }

        DB::table('pemilik')->where('idpemilik', $id)->delete();

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil dihapus.');
    }

    // ==================== VALIDASI & HELPER ====================

    protected function validatePemilik(Request $request, $id = null)
    {
        $userUniqueRule = $id
            ? 'unique:pemilik,iduser,' . $id . ',idpemilik'
            : 'unique:pemilik,iduser';

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

    protected function formatNoWa($no)
    {
        $no = preg_replace('/[^0-9]/', '', $no);
        if (substr($no, 0, 1) === '0') {
            $no = '62' . substr($no, 1);
        }
        return $no;
    }
}
