<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Pemilik;
use App\Models\User;

class RegistrasiPemilikController extends Controller
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

        return view('rshp.resepsionis.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        // Ambil user yang belum punya pemilik
        $users = DB::table('user')
            ->whereNotIn('iduser', function ($query) {
                $query->select('iduser')->from('pemilik');
            })
            ->orderBy('nama', 'asc')
            ->get();

        return view('rshp.resepsionis.pemilik.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePemilik($request);

        DB::table('pemilik')->insert([
            'iduser' => $validated['iduser'],
            'alamat' => $validated['alamat'],
            'no_wa'  => $this->formatNoWa($validated['no_wa']),
        ]);

        return redirect()->route('resepsionis.pemilik.index')
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

        return view('rshp.resepsionis.pemilik.edit', compact('pemilik'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:500',
            'email'  => 'required|email|max:200',
            'alamat' => 'required|string|max:1000',
            'no_wa'  => 'required|string|max:45',
        ]);

        $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();

        if (!$pemilik) {
            return redirect()->route('resepsionis.pemilik.index')
                ->with('error', 'Data pemilik tidak ditemukan.');
        }

        // Update user
        DB::table('user')
            ->where('iduser', $pemilik->iduser)
            ->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);

        // Update pemilik
        DB::table('pemilik')
            ->where('idpemilik', $id)
            ->update([
                'alamat' => $request->alamat,
                'no_wa'  => $request->no_wa,
            ]);

        return redirect()->route('resepsionis.pemilik.index')
            ->with('success', 'Data pemilik berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();

        if (!$pemilik) {
            return redirect()->route('resepsionis.pemilik.index')
                ->with('error', 'Pemilik tidak ditemukan.');
        }

        // Cek apakah pemilik masih punya pet
        $jumlahPet = DB::table('pet')
            ->where('idpemilik', $id)
            ->count();

        if ($jumlahPet > 0) {
            return redirect()->route('resepsionis.pemilik.index')
                ->with('error', 'Pemilik tidak dapat dihapus karena masih memiliki pet.');
        }

        DB::table('pemilik')->where('idpemilik', $id)->delete();

        return redirect()->route('resepsionis.pemilik.index')
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
            $no = '+62' . substr($no, 1);
        }
        return $no;
    }
}
