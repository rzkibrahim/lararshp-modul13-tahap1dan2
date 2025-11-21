<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPerawatController extends Controller
{
    public function index()
    {
        // Ambil ID user dari session (bukan idrole_user)
        $userId = session('user_id');

        // Dapatkan idrole_user dokter yang sedang login
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 3) // 3 = Perawat
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('home')->with('error', 'Data perawat tidak ditemukan');
        }

        $perawatId = $roleUser->idrole_user; // Ini yang dipakai di rekam_medis.dokter_pemeriksa

        // Hitung statistik
        $today = Carbon::today();

        // Get statistics for dashboard
        $totalPasienHariIni = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', today())
            ->count();

        $tindakanHariIni = DB::table('detail_rekam_medis as drm')
            ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
            ->whereDate('rm.created_at', today())
            ->count();

        $menungguPerawatan = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', today())
            ->where('status', 0)
            ->count();

        $totalPerawatan = DB::table('detail_rekam_medis')->count();

        // Get pasien hari ini
        $pasienHariIni = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'jh.nama_jenis_hewan'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->whereDate('td.waktu_daftar', today())
            ->orderBy('td.no_urut', 'asc')
            ->get();

        // Stats for sidebar
        $pasienDirawat = $totalPasienHariIni;
        $totalTindakan = $tindakanHariIni;
        $pasienMenunggu = $menungguPerawatan;

        return view('rshp.perawat.dashboard-perawat', compact(
            'totalPasienHariIni',
            'tindakanHariIni',
            'menungguPerawatan',
            'totalPerawatan',
            'pasienHariIni',
            'pasienDirawat',
            'totalTindakan',
            'pasienMenunggu'
        ));
    }
}