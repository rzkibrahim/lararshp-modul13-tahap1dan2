<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasiListController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        
        // ✅ SAMAKAN DENGAN DASHBOARD: Dapatkan idrole_user pemilik
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 5) // 5 = Pemilik
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $pemilikId = $roleUser->idrole_user; // ✅ GUNAKAN idrole_user

        // Get all pets for dropdown
        $pets = DB::table('pet')
            ->where('idpemilik', $pemilikId) // ✅ GUNAKAN $pemilikId (idrole_user)
            ->orderBy('nama', 'asc')
            ->get();

        // Get current reservations
        $reservasiList = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'td.idpet',
                'td.idrole_user',
                'p.nama as nama_pet',
                'jh.nama_jenis_hewan',
                'rh.nama_ras',
                'u_dokter.nama as nama_dokter'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where('p.idpemilik', $pemilikId) // ✅ GUNAKAN $pemilikId (idrole_user)
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();

        return view('rshp.pemilik.reservasi', compact('pets', 'reservasiList'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        
        // ✅ SAMAKAN DENGAN DASHBOARD: Dapatkan idrole_user pemilik
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 5) // 5 = Pemilik
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $pemilikId = $roleUser->idrole_user; // ✅ GUNAKAN idrole_user

        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'tanggal_kunjungan' => 'required|date',
        ]);

        try {
            // Verify pet belongs to this pemilik
            $pet = DB::table('pet')
                ->where('idpet', $request->idpet)
                ->where('idpemilik', $pemilikId) // ✅ GUNAKAN $pemilikId (idrole_user)
                ->first();

            if (!$pet) {
                return redirect()->back()->with('error', 'Pet tidak ditemukan atau bukan milik Anda');
            }

            // Generate nomor urut untuk tanggal tersebut
            $tanggal = date('Y-m-d', strtotime($request->tanggal_kunjungan));
            $noUrut = DB::table('temu_dokter')
                ->whereDate('waktu_daftar', $tanggal)
                ->max('no_urut');
            $noUrut = $noUrut ? $noUrut + 1 : 1;

            // Create reservation
            DB::table('temu_dokter')->insert([
                'no_urut' => $noUrut,
                'waktu_daftar' => $request->tanggal_kunjungan,
                'status' => 0, // Menunggu
                'idpet' => $request->idpet,
                'idrole_user' => $roleUser->idrole_user, // ✅ GUNAKAN idrole_user dari pemilik
            ]);

            return redirect()->route('pemilik.reservasi.list')->with('success', 'Reservasi berhasil dibuat dengan nomor urut ' . $noUrut);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat reservasi: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $userId = session('user_id');
        
        // ✅ SAMAKAN DENGAN DASHBOARD: Dapatkan idrole_user pemilik
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 5) // 5 = Pemilik
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $pemilikId = $roleUser->idrole_user; // ✅ GUNAKAN idrole_user

        try {
            // Verify reservation belongs to this pemilik's pet
            $reservasi = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->where('td.idreservasi_dokter', $id)
                ->where('p.idpemilik', $pemilikId) // ✅ GUNAKAN $pemilikId (idrole_user)
                ->where('td.status', 0) // Only cancel pending reservations
                ->first();

            if (!$reservasi) {
                return redirect()->back()->with('error', 'Reservasi tidak ditemukan atau tidak dapat dibatalkan');
            }

            // Update status to cancelled (2)
            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $id)
                ->update(['status' => 2]);

            return redirect()->route('pemilik.reservasi.list')->with('success', 'Reservasi berhasil dibatalkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan reservasi: ' . $e->getMessage());
        }
    }
}