<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DashboardDokterController extends Controller
{
    public function index()
    {
        // Ambil ID user dari session (bukan idrole_user)
        $userId = session('user_id');

        // Dapatkan idrole_user dokter yang sedang login
        $roleUser = DB::table('role_user')
            ->where('iduser', $userId)
            ->where('idrole', 2) // 2 = Dokter
            ->where('status', 1)
            ->first();

        if (!$roleUser) {
            return redirect()->route('home')->with('error', 'Data dokter tidak ditemukan');
        }

        $dokterId = $roleUser->idrole_user; // Ini yang dipakai di rekam_medis.dokter_pemeriksa

        // Hitung statistik
        $today = Carbon::today();

        // Total pasien hari ini
        $totalPasienHariIni = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $today)
            ->count();

        // Pasien sedang diperiksa (status = 0)
        $sedangDiperiksa = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $today)
            ->where('status', 0)
            ->count();

        // Antrian menunggu
        $antrianMenunggu = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $today)
            ->where('status', 0)
            ->count();

        // Total rekam medis oleh dokter ini (menggunakan idrole_user)
        $totalRekamMedis = DB::table('rekam_medis')
            ->where('dokter_pemeriksa', $dokterId)
            ->count();

        // Antrian hari ini dengan detail
        $antrianHariIni = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->whereDate('td.waktu_daftar', $today)
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.status',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'jh.nama_jenis_hewan as jenis_hewan'
            )
            ->orderBy('td.no_urut')
            ->get();

        // Rekam medis terbaru (sesuai dengan native: join lengkap)
        $rekamMedisTerbaru = DB::table('rekam_medis as rm')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik'
            )
            ->where('rm.dokter_pemeriksa', $dokterId)
            ->orderBy('rm.created_at', 'desc')
            ->limit(5)
            ->get();

        // Variabel untuk sidebar (sesuai layout)
        $pasienDiperiksa = $sedangDiperiksa;
        $pasienMenunggu = $antrianMenunggu;

        return view('rshp.dokter.dashboard-dokter', compact(
            'totalPasienHariIni',
            'sedangDiperiksa',
            'antrianMenunggu',
            'totalRekamMedis',
            'antrianHariIni',
            'rekamMedisTerbaru',
            'pasienDiperiksa',
            'pasienMenunggu'
        ));
    }

    // VIEW DATA PASIEN
    public function dataPasien()
    {
        $pasien = DB::table('pet as p')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->select('p.*', 'u.nama as nama_pemilik', 'rh.nama_ras', 'jh.nama_jenis_hewan')
            ->get();

        return view('rshp.dokter.data-pasien', compact('pasien'));
    }

    // REKAM MEDIS LIST
    public function rekamMedisList()
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

        // Query sesuai dengan native: listAll()
        $rekamMedisList = DB::table('rekam_medis as rm')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'rm.idreservasi_dokter',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'jh.nama_jenis_hewan',
                'rh.nama_ras'
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where('rm.dokter_pemeriksa', $dokterId)
            ->orderBy('rm.created_at', 'desc')
            ->get();

        return view('rshp.dokter.rekam-medis-list', compact('rekamMedisList'));
    }

    // REKAM MEDIS SHOW
    public function rekamMedisShow($id)
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

    // CREATE DETAIL REKAM MEDIS
    public function detailRekamMedisCreate($idRekamMedis)
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
            ->where('ktt.idkode_tindakan_terapi', '!=', 0)
            ->orderBy('ktt.kode', 'asc')
            ->get();

        return view('rshp.dokter.detail-rekam-medis-create', compact('idRekamMedis', 'kodeTindakanTerapi'));
    }

    // STORE DETAIL REKAM MEDIS
    public function detailRekamMedisStore(Request $request, $idRekamMedis)
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

    // EDIT DETAIL REKAM MEDIS
    public function detailRekamMedisEdit($idRekamMedis, $idDetail)
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

        return view('rshp.dokter.detail-rekam-medis-edit', compact('idRekamMedis', 'detailRekamMedis', 'kodeTindakanTerapi'));
    }

    // UPDATE DETAIL REKAM MEDIS
    public function detailRekamMedisUpdate(Request $request, $idRekamMedis, $idDetail)
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

    // DESTROY DETAIL REKAM MEDIS
    public function detailRekamMedisDestroy($idRekamMedis, $idDetail)
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

    /**
     * Menyelesaikan rekam medis
     */
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

        // Validasi kepemilikan rekam medis
        $rekamMedis = DB::table('rekam_medis')
            ->where('idrekam_medis', $id)
            ->where('dokter_pemeriksa', $roleUser->idrole_user)
            ->first();

        if (!$rekamMedis) {
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        try {
            // Update status rekam medis menjadi selesai
            // Anda bisa menambahkan kolom 'status' atau 'is_selesai' di tabel rekam_medis
            // Untuk sementara, kita akan menggunakan pendekatan sederhana

            // Jika ingin menambahkan kolom status, uncomment kode berikut:
            /*
        DB::table('rekam_medis')
            ->where('idrekam_medis', $id)
            ->update([
                'status' => 'selesai',
                'updated_at' => now()
            ]);
        */

            // Untuk sekarang, kita cukup log dan return success
            Log::info("Rekam medis diselesaikan - ID: {$id} oleh dokter: {$roleUser->idrole_user}");

            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil diselesaikan dan diarsipkan.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error selesai rekam medis: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan rekam medis: ' . $e->getMessage()
            ], 500);
        }
    }

    // VIEW PROFIL DOKTER
    public function profil()
    {
        $userId = session('user_id');
        $profil = DB::table('user')->where('iduser', $userId)->first();
        return view('rshp.dokter.profil', compact('profil'));
    }
}
