<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisPemController extends Controller
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

        // Get rekam medis for all pets of this pemilik
        $rekamMedisList = DB::table('rekam_medis as rm')
            ->select(
                'rm.idrekam_medis',
                'rm.idreservasi_dokter',
                'rm.idpet',
                'rm.created_at',
                'rm.anamnesa',
                'rm.temuan_klinis',
                'rm.diagnosa',
                'rm.dokter_pemeriksa',
                'p.nama as nama_pet',
                'jh.nama_jenis_hewan',
                'rh.nama_ras',
                'u_dokter.nama as nama_dokter'
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where('p.idpemilik', $pemilikId)
            ->orderBy('rm.created_at', 'desc')
            ->get();

        return view('rshp.pemilik.rekam-medis', compact('rekamMedisList'));
    }

    public function show($id)
    {
        $userId = session('user_id');

        $pemilik = DB::table('pemilik')
            ->where('iduser', $userId)
            ->first();

        if (!$pemilik) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan');
        }

        // Get rekam medis detail
        $rekamMedis = DB::table('rekam_medis as rm')
            ->select(
                'rm.idrekam_medis',
                'rm.idreservasi_dokter',
                'rm.idpet',
                'rm.created_at',
                'rm.anamnesa',
                'rm.temuan_klinis',
                'rm.diagnosa',
                'rm.dokter_pemeriksa',
                'p.nama as nama_pet',
                'p.tanggal_lahir',
                'p.jenis_kelamin',
                'p.warna_tanda',
                'jh.nama_jenis_hewan',
                'rh.nama_ras',
                'u_dokter.nama as nama_dokter'
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->leftJoin('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->leftJoin('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as u_dokter', 'ru.iduser', '=', 'u_dokter.iduser')
            ->where('rm.idrekam_medis', $id)
            ->where('p.idpemilik', $pemilik->idpemilik)
            ->first();

        if (!$rekamMedis) {
            return redirect()->route('pemilik.rekammedis.list')->with('error', 'Rekam medis tidak ditemukan');
        }

        // Get detail tindakan/terapi
        $detailRekamMedis = DB::table('detail_rekam_medis as drm')
            ->select(
                'drm.iddetail_rekam_medis',
                'drm.idrekam_medis',
                'drm.idkode_tindakan_terapi',
                'drm.detail',
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->leftJoin('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->leftJoin('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->where('drm.idrekam_medis', $id)
            ->orderBy('drm.iddetail_rekam_medis', 'desc')
            ->get();

        return view('rshp.pemilik.rekam-medis-detail', compact('rekamMedis', 'detailRekamMedis'));
    }
}
