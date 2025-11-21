<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPemilikController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        
        // ✅ SAMAKAN DENGAN DOKTER: Dapatkan idrole_user pemilik
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 5) // 5 = Pemilik
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $pemilikId = $roleUser->idrole_user; // ✅ GUNAKAN idrole_user seperti di dokter

        // Get statistics - ✅ PERBAIKAN: Gunakan $pemilikId (idrole_user)
        $totalPet = DB::table('pet')
            ->where('idpemilik', $pemilikId)
            ->count();

        $totalKunjungan = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->where('p.idpemilik', $pemilikId)
            ->count();

        $menungguAntrian = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->where('p.idpemilik', $pemilikId)
            ->where('td.status', 0)
            ->count();

        $kunjunganBulanIni = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->where('p.idpemilik', $pemilikId)
            ->whereMonth('td.waktu_daftar', date('m'))
            ->whereYear('td.waktu_daftar', date('Y'))
            ->count();

        // Get list of pets
        $listPet = DB::table('pet as p')
            ->select(
                'p.idpet',
                'p.nama',
                'p.tanggal_lahir',
                'p.warna_tanda',
                'p.jenis_kelamin',
                'p.idpemilik',
                'p.idras_hewan',
                'jh.nama_jenis_hewan',
                'rh.nama_ras'
            )
            ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where('p.idpemilik', $pemilikId)
            ->orderBy('p.nama', 'asc')
            ->get();

        // Get recent visit history
        $riwayatKunjungan = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'td.idpet',
                'p.nama as nama_pet',
                'u.nama as nama_dokter'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('p.idpemilik', $pemilikId)
            ->orderBy('td.waktu_daftar', 'desc')
            ->limit(5)
            ->get();

        return view('rshp.pemilik.dashboard-pemilik', compact(
            'totalPet',
            'totalKunjungan',
            'menungguAntrian',
            'kunjunganBulanIni',
            'listPet',
            'riwayatKunjungan'
        ));
    }
}