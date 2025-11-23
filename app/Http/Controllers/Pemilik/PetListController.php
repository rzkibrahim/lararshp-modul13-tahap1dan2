<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetListController extends Controller
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

        // Get all pets for this pemilik
        $pets = DB::table('pet as p')
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
            ->where('p.idpemilik', $pemilikId) // ✅ GUNAKAN $pemilikId (idrole_user)
            ->orderBy('p.nama', 'asc')
            ->get();

        return view('rshp.pemilik.pet', compact('pets'));
    }

    public function show($id)
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

        $pet = DB::table('pet as p')
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
            ->where('p.idpet', $id)
            ->where('p.idpemilik', $pemilikId) // ✅ GUNAKAN $pemilikId (idrole_user)
            ->first();

        if (!$pet) {
            return redirect()->route('pemilik.pet.list')->with('error', 'Pet tidak ditemukan');
        }

        // Get riwayat kunjungan pet ini dengan COUNT rekam medis
        $riwayatKunjungan = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'u.nama as nama_dokter',
                DB::raw('COUNT(rm.idrekam_medis) as has_rekam_medis')
            )
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('td.idpet', $id)
            ->groupBy('td.idreservasi_dokter', 'td.no_urut', 'td.waktu_daftar', 'td.status', 'u.nama')
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();

        return view('rshp.pemilik.pet-detail', compact('pet', 'riwayatKunjungan'));
    }
}