<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RekamMedisListController extends Controller
{
    /**
     * Menampilkan daftar semua rekam medis (sesuai rekam-medis-list.php)
     * Query sesuai dengan method listAll() di Class RekamMedis native
     */
    // Di RekamMedisListController.php
    public function index(Request $request)
    {
        $userId = session('user_id');

        // Dapatkan idrole_user dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Data dokter tidak ditemukan');
        }

        $dokterId = $roleUser->idrole_user;
        $status = $request->get('status', 'active');

        // ✅ QUERY YANG TERHUBUNG DENGAN TEMU_DOKTER
        $rekamMedisList = DB::table('rekam_medis as rm')
            ->select(
                'rm.*',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'jh.nama_jenis_hewan',
                'rh.nama_ras',
                'td.no_urut',
                'td.waktu_daftar'
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->where('rm.dokter_pemeriksa', $dokterId)
            ->orderBy('rm.created_at', 'desc')
            ->get();

        return view('rshp.dokter.rekam-medis-list', compact('rekamMedisList', 'status'));
    }

    public function selesaiRekamMedis($id)
    {
        $userId = session('user_id');

        // Validasi akses dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return response()->json([
                'success' => false,
                'message' => 'Data dokter tidak ditemukan'
            ], 403);
        }

        // ✅ PAKAI ELOQUENT
        $rekamMedis = RekamMedis::where('idrekam_medis', $id)
            ->where('dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$rekamMedis) {
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        try {
            // ✅ PAKAI RELATIONSHIP
            $rekamMedis->temuDokter()->update(['status' => 1]);

            Log::info("Rekam medis diselesaikan - ID: {$id}");

            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil diselesaikan.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error selesai rekam medis: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan rekam medis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail rekam medis (sesuai rekam-medis-detail-doc.php)
     * Query sesuai dengan method findById() di Class RekamMedis native
     */
    public function show($id)
    {
        $userId = session('user_id');

        // Dapatkan idrole_user dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('dokter.rekam-medis.list')
                ->with('error', 'Data dokter tidak ditemukan');
        }

        $dokterId = $roleUser->idrole_user;

        // Query sesuai dengan native: findById()
        $rekamMedis = DB::table('rekam_medis as rm')
            ->select(
                'rm.*',
                'p.nama as nama_pet',
                'u_pemilik.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.idreservasi_dokter',
                'jh.nama_jenis_hewan',
                'rh.nama_ras'
            )
            ->join('temu_dokter as td', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u_pemilik', 'u_pemilik.iduser', '=', 'pem.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'rm.dokter_pemeriksa')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->where('rm.idrekam_medis', $id)
            ->where('rm.dokter_pemeriksa', $dokterId) // Validasi akses
            ->first();

        if (!$rekamMedis) {
            return redirect()->route('dokter.rekam-medis.list')
                ->with('error', 'Rekam medis tidak ditemukan atau Anda tidak memiliki akses');
        }

        // Query sesuai dengan native: getDetailsByRekamId()
        $detailRekamMedis = DB::table('detail_rekam_medis as drm')
            ->select(
                'drm.*',
                'kt.kode',
                'kt.deskripsi_tindakan_terapi',
                'k.nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->join('kode_tindakan_terapi as kt', 'drm.idkode_tindakan_terapi', '=', 'kt.idkode_tindakan_terapi')
            ->leftJoin('kategori as k', 'kt.idkategori', '=', 'k.idkategori')
            ->leftJoin('kategori_klinis as kk', 'kt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->where('drm.idrekam_medis', $id)
            ->orderBy('drm.iddetail_rekam_medis', 'desc')
            ->get();

        return view('rshp.dokter.rekam-medis-detail', compact('rekamMedis', 'detailRekamMedis'));
    }

    public function create($idRekamMedis)
    {
        $userId = session('user_id');

        // Validasi akses dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Data dokter tidak ditemukan');
        }

        // Validasi kepemilikan rekam medis
        $rekamMedis = DB::table('rekam_medis')
            ->where('idrekam_medis', $idRekamMedis)
            ->where('dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$rekamMedis) {
            return redirect()->route('dokter.rekam-medis.list')
                ->with('error', 'Rekam medis tidak ditemukan atau Anda tidak memiliki akses');
        }

        // Ambil data kode tindakan/terapi
        $kodeTindakanTerapi = DB::table('kode_tindakan_terapi as ktt')
            ->select(
                'ktt.idkode_tindakan_terapi',
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->leftJoin('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->leftJoin('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->where('ktt.idkode_tindakan_terapi', '!=', 0) // Pastikan data valid
            ->orderBy('ktt.kode', 'asc')
            ->get();

        return view('rshp.dokter.create', compact('idRekamMedis', 'kodeTindakanTerapi'));
    }

    /**
     * Menyimpan detail rekam medis baru
     */
    public function store(Request $request, $idRekamMedis)
    {
        $userId = session('user_id');

        // Validasi akses dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Data dokter tidak ditemukan');
        }

        // Validasi kepemilikan rekam medis
        $rekamMedis = DB::table('rekam_medis')
            ->where('idrekam_medis', $idRekamMedis)
            ->where('dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$rekamMedis) {
            return redirect()->route('dokter.rekam-medis.list')
                ->with('error', 'Rekam medis tidak ditemukan atau Anda tidak memiliki akses');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Insert detail rekam medis
            $idDetail = DB::table('detail_rekam_medis')->insertGetId([
                'idrekam_medis' => $idRekamMedis,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

            Log::info("Detail rekam medis berhasil ditambahkan: ID {$idDetail} untuk rekam medis {$idRekamMedis}");

            return redirect()->route('dokter.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Detail tindakan/terapi berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error("Error menambah detail rekam medis: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambah detail tindakan/terapi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan form edit detail rekam medis
     */
    public function edit($idRekamMedis, $idDetail)
    {
        $userId = session('user_id');

        // Validasi akses dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Data dokter tidak ditemukan');
        }

        // Validasi kepemilikan rekam medis dan detail
        $detailRekamMedis = DB::table('detail_rekam_medis as drm')
            ->select('drm.*')
            ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
            ->where('drm.iddetail_rekam_medis', $idDetail)
            ->where('drm.idrekam_medis', $idRekamMedis)
            ->where('rm.dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$detailRekamMedis) {
            return redirect()->route('dokter.rekam-medis.list')
                ->with('error', 'Detail rekam medis tidak ditemukan atau Anda tidak memiliki akses');
        }

        // Ambil data kode tindakan/terapi
        $kodeTindakanTerapi = DB::table('kode_tindakan_terapi as ktt')
            ->select(
                'ktt.idkode_tindakan_terapi',
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->leftJoin('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->leftJoin('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->where('ktt.idkode_tindakan_terapi', '!=', 0)
            ->orderBy('ktt.kode', 'asc')
            ->get();

        return view('rshp.dokter.edit', compact('idRekamMedis', 'detailRekamMedis', 'kodeTindakanTerapi'));
    }

    /**
     * Update detail rekam medis
     */
    public function update(Request $request, $idRekamMedis, $idDetail)
    {
        $userId = session('user_id');

        // Validasi akses dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Data dokter tidak ditemukan');
        }

        // Validasi kepemilikan rekam medis dan detail
        $detailRekamMedis = DB::table('detail_rekam_medis as drm')
            ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
            ->where('drm.iddetail_rekam_medis', $idDetail)
            ->where('drm.idrekam_medis', $idRekamMedis)
            ->where('rm.dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$detailRekamMedis) {
            return redirect()->route('dokter.rekam-medis.list')
                ->with('error', 'Detail rekam medis tidak ditemukan atau Anda tidak memiliki akses');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update detail rekam medis
            DB::table('detail_rekam_medis')
                ->where('iddetail_rekam_medis', $idDetail)
                ->where('idrekam_medis', $idRekamMedis)
                ->update([
                    'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                    'detail' => $request->detail,
                ]);

            Log::info("Detail rekam medis berhasil diupdate: ID {$idDetail} untuk rekam medis {$idRekamMedis}");

            return redirect()->route('dokter.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Detail tindakan/terapi berhasil diupdate');
        } catch (\Exception $e) {
            Log::error("Error update detail rekam medis: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate detail tindakan/terapi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus detail rekam medis
     */
    public function destroy($idRekamMedis, $idDetail)
    {
        $userId = session('user_id');

        // Validasi akses dokter
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2)
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return response()->json([
                'success' => false,
                'message' => 'Data dokter tidak ditemukan'
            ], 403);
        }

        // Validasi kepemilikan rekam medis dan detail
        $detailRekamMedis = DB::table('detail_rekam_medis as drm')
            ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
            ->where('drm.iddetail_rekam_medis', $idDetail)
            ->where('drm.idrekam_medis', $idRekamMedis)
            ->where('rm.dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$detailRekamMedis) {
            return response()->json([
                'success' => false,
                'message' => 'Detail rekam medis tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        try {
            // Hapus detail rekam medis
            DB::table('detail_rekam_medis')
                ->where('iddetail_rekam_medis', $idDetail)
                ->where('idrekam_medis', $idRekamMedis)
                ->delete();

            Log::info("Detail rekam medis berhasil dihapus: ID {$idDetail} untuk rekam medis {$idRekamMedis}");

            return response()->json([
                'success' => true,
                'message' => 'Detail tindakan/terapi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error("Error hapus detail rekam medis: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus detail tindakan/terapi: ' . $e->getMessage()
            ], 500);
        }
    }
}
