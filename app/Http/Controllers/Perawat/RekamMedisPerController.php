<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekamMedisPerController extends Controller
{
    // INDEX - List reservasi dan rekam medis dengan filter tanggal
    public function index(Request $request)
    {
        // Handle navigation actions terlebih dahulu
        if ($request->has('action')) {
            switch ($request->action) {
                case 'prev_month':
                    $bulan = Carbon::parse($request->bulan)->subMonth()->format('Y-m');
                    return redirect()->route('perawat.rekam-medis.index', ['bulan' => $bulan]);
                    
                case 'next_month':
                    $bulan = Carbon::parse($request->bulan)->addMonth()->format('Y-m');
                    return redirect()->route('perawat.rekam-medis.index', ['bulan' => $bulan]);
                    
                case 'filter':
                    // Tetap lanjut ke proses normal
                    break;
            }
        }

        // Default tanggal hari ini
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $bulan = $request->get('bulan', date('Y-m'));
        $tahun = $request->get('tahun', date('Y'));

        // Ambil reservasi tanpa rekam medis dengan filter
        $reservasiQuery = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.idpet',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->whereNull('rm.idrekam_medis');

        // Filter berdasarkan tanggal
        if ($request->has('tanggal') && $request->tanggal) {
            $reservasiQuery->whereDate('td.waktu_daftar', $tanggal);
        } elseif ($request->has('bulan') && $request->bulan) {
            $reservasiQuery->whereYear('td.waktu_daftar', date('Y', strtotime($bulan)))
                ->whereMonth('td.waktu_daftar', date('m', strtotime($bulan)));
        } elseif ($request->has('tahun') && $request->tahun) {
            $reservasiQuery->whereYear('td.waktu_daftar', $tahun);
        }

        $reservasi = $reservasiQuery->orderBy('td.waktu_daftar', 'desc')->get();

        // Ambil rekam medis yang sudah ada dengan filter - INI YANG DIPERBAIKI
        $listRMQuery = DB::table('rekam_medis as rm')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'rm.idreservasi_dokter',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'td.waktu_daftar'
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter');

        // Filter berdasarkan tanggal untuk rekam medis
        if ($request->has('tanggal') && $request->tanggal) {
            $listRMQuery->whereDate('rm.created_at', $tanggal);
        } elseif ($request->has('bulan') && $request->bulan) {
            $listRMQuery->whereYear('rm.created_at', date('Y', strtotime($bulan)))
                ->whereMonth('rm.created_at', date('m', strtotime($bulan)));
        } elseif ($request->has('tahun') && $request->tahun) {
            $listRMQuery->whereYear('rm.created_at', $tahun);
        }

        $listRM = $listRMQuery->orderBy('rm.created_at', 'desc')->get();

        // Statistik untuk kalender
        $statistik = $this->getStatistikKalender($request);

        return view('rshp.perawat.rekam-medis.index', compact(
            'reservasi',
            'listRM', // SEKARANG VARIABLE INI SUDAH TERDEFINISI
            'tanggal',
            'bulan',
            'tahun',
            'statistik'
        ));
    }

    // Method untuk mendapatkan statistik kalender
    private function getStatistikKalender($request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $bulan = $request->get('bulan', date('Y-m'));
        $tahun = $request->get('tahun', date('Y'));

        $statistik = [
            'total_reservasi' => 0,
            'total_rekam_medis' => 0,
            'reservasi_per_hari' => [],
            'rekam_medis_per_hari' => [],
            'reservasi_per_bulan' => [],
            'rekam_medis_per_bulan' => []
        ];

        // Jika filter per hari
        if ($request->has('tanggal') && $request->tanggal) {
            $statistik['total_reservasi'] = DB::table('temu_dokter')
                ->whereDate('waktu_daftar', $tanggal)
                ->count();

            $statistik['total_rekam_medis'] = DB::table('rekam_medis')
                ->whereDate('created_at', $tanggal)
                ->count();
        }
        // Jika filter per bulan
        elseif ($request->has('bulan') && $request->bulan) {
            $year = date('Y', strtotime($bulan));
            $month = date('m', strtotime($bulan));

            $statistik['total_reservasi'] = DB::table('temu_dokter')
                ->whereYear('waktu_daftar', $year)
                ->whereMonth('waktu_daftar', $month)
                ->count();

            $statistik['total_rekam_medis'] = DB::table('rekam_medis')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // Data per hari dalam bulan
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

                $reservasiCount = DB::table('temu_dokter')
                    ->whereDate('waktu_daftar', $currentDate)
                    ->count();

                $rekamMedisCount = DB::table('rekam_medis')
                    ->whereDate('created_at', $currentDate)
                    ->count();

                $statistik['reservasi_per_hari'][$currentDate] = $reservasiCount;
                $statistik['rekam_medis_per_hari'][$currentDate] = $rekamMedisCount;
            }
        }
        // Jika filter per tahun
        elseif ($request->has('tahun') && $request->tahun) {
            $statistik['total_reservasi'] = DB::table('temu_dokter')
                ->whereYear('waktu_daftar', $tahun)
                ->count();

            $statistik['total_rekam_medis'] = DB::table('rekam_medis')
                ->whereYear('created_at', $tahun)
                ->count();

            // Data per bulan dalam tahun
            for ($month = 1; $month <= 12; $month++) {
                $monthFormatted = sprintf('%02d', $month);
                
                $reservasiCount = DB::table('temu_dokter')
                    ->whereYear('waktu_daftar', $tahun)
                    ->whereMonth('waktu_daftar', $monthFormatted)
                    ->count();

                $rekamMedisCount = DB::table('rekam_medis')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $monthFormatted)
                    ->count();

                $statistik['reservasi_per_bulan'][$month] = $reservasiCount;
                $statistik['rekam_medis_per_bulan'][$month] = $rekamMedisCount;
            }
        }

        return $statistik;
    }

    // CREATE - Form buat rekam medis baru
    public function create($idReservasi, $idPet)
    {
        // Cek apakah sudah ada rekam medis
        $exist = DB::table('rekam_medis')
            ->where('idreservasi_dokter', $idReservasi)
            ->first();

        if ($exist) {
            return redirect()->route('perawat.rekam-medis.detail', $exist->idrekam_medis)
                ->with('success', 'Rekam medis sudah ada');
        }

        // Info reservasi
        $info = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.idpet',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->where('td.idreservasi_dokter', $idReservasi)
            ->first();

        if (!$info) {
            return redirect()->route('perawat.rekam-medis.index')
                ->with('error', 'Reservasi tidak ditemukan');
        }

        // Daftar dokter
        $listDokter = DB::table('role_user as ru')
            ->select('ru.idrole_user', 'u.nama')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2) // Role dokter
            ->where('ru.status', 1)
            ->orderBy('u.nama')
            ->get();

        return view('rshp.perawat.rekam-medis.create', compact('info', 'listDokter'));
    }

    // STORE - Simpan rekam medis baru
    public function store(Request $request)
    {
        $request->validate([
            'idreservasi' => 'required|exists:temu_dokter,idreservasi_dokter',
            'idpet' => 'required|exists:pet,idpet',
            'dokter_pemeriksa' => 'required|exists:role_user,idrole_user',
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Buat rekam medis
            $idRekam = DB::table('rekam_medis')->insertGetId([
                'idreservasi_dokter' => $request->idreservasi,
                'idpet' => $request->idpet,
                'dokter_pemeriksa' => $request->dokter_pemeriksa,
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
                'created_at' => now(),
            ]);

            // Update status temu_dokter jadi selesai
            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $request->idreservasi)
                ->update(['status' => 1]);

            DB::commit();

            return redirect()->route('perawat.rekam-medis.detail', $idRekam)
                ->with('success', 'Rekam medis berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat rekam medis: ' . $e->getMessage());
        }
    }

    // DETAIL - Edit header dan manage tindakan
    public function detail($id)
    {
        // Header rekam medis
        $header = DB::table('rekam_medis as rm')
            ->select(
                'rm.*',
                'p.nama as nama_pet',
                'u_pemilik.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.idreservasi_dokter'
            )
            ->join('temu_dokter as td', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u_pemilik', 'pem.iduser', '=', 'u_pemilik.iduser')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'rm.dokter_pemeriksa')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->where('rm.idrekam_medis', $id)
            ->first();

        if (!$header) {
            return redirect()->route('perawat.rekam-medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        }

        // Detail tindakan
        $detailTindakan = DB::table('detail_rekam_medis as drm')
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

        // Master kode tindakan
        $listKode = DB::table('kode_tindakan_terapi')
            ->select('idkode_tindakan_terapi', DB::raw("CONCAT(kode, ' - ', deskripsi_tindakan_terapi) as label"))
            ->orderBy('kode', 'asc')
            ->get();

        return view('rshp.perawat.rekam-medis.detail', compact('header', 'detailTindakan', 'listKode'));
    }

    // UPDATE_HEADER - Update data pemeriksaan
    public function updateHeader(Request $request, $id)
    {
        $request->validate([
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'nullable|string',
        ]);

        $updated = DB::table('rekam_medis')
            ->where('idrekam_medis', $id)
            ->update([
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
            ]);

        if ($updated) {
            return redirect()->route('perawat.rekam-medis.detail', $id)
                ->with('success', 'Data pemeriksaan berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data pemeriksaan');
    }

    // CREATE_DETAIL - Tambah tindakan
    public function createDetail(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        $created = DB::table('detail_rekam_medis')->insert([
            'idrekam_medis' => $id,
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail,
        ]);

        if ($created) {
            return redirect()->route('perawat.rekam-medis.detail', $id)
                ->with('success', 'Tindakan berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan tindakan');
    }

    // UPDATE_DETAIL - Update tindakan
    public function updateDetail(Request $request, $id, $idDetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        $updated = DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $idDetail)
            ->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

        if ($updated) {
            return redirect()->route('perawat.rekam-medis.detail', $id)
                ->with('success', 'Tindakan berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui tindakan');
    }

    // DELETE_DETAIL - Hapus tindakan
    public function deleteDetail(Request $request, $id, $idDetail)
    {
        $deleted = DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $idDetail)
            ->delete();

        if ($deleted) {
            return redirect()->route('perawat.rekam-medis.detail', $id)
                ->with('success', 'Tindakan berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus tindakan');
    }
}