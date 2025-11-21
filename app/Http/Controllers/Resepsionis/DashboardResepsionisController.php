<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardResepsionisController extends Controller
{
    public function index()
    {
        // Statistik untuk hari ini
        $totalAntrianHariIni = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', today())
            ->count();

        $antrianMenunggu = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', today())
            ->where('status', 0)
            ->count();

        $antrianSelesai = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', today())
            ->where('status', 1)
            ->count();

        $pemilikAktifHariIni = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->whereDate('td.waktu_daftar', today())
            ->distinct('pm.idpemilik')
            ->count('pm.idpemilik');

        $totalPet = DB::table('pet')->count();

        // Antrian hari ini
        $antrianHariIni = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->whereDate('td.waktu_daftar', today())
            ->orderBy('td.no_urut', 'asc')
            ->get();

        return view('rshp.resepsionis.dashboard-resepsionis', compact(
            'totalAntrianHariIni',
            'antrianMenunggu',
            'antrianSelesai',
            'pemilikAktifHariIni',
            'totalPet',
            'antrianHariIni'
        ));
    }
}