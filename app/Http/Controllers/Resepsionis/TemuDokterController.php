<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TemuDokter;
use App\Models\Pet;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Pemilik;


class TemuDokterController extends Controller
{
    public function index(Request $request)
    {
        // Tanggal untuk melihat antrian
        $selectedDate = $request->get('date', date('Y-m-d'));

        // Tanggal untuk mendaftar
        $selectedDaftarDate = $request->get('tanggal_daftar', date('Y-m-d'));

        // Get antrian hari ini dengan join yang benar
        $antrian = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'p.nama as nama_pet',
                'u_dokter.nama as nama_dokter',
                'u_pemilik.nama as nama_pemilik'
            )
            ->leftJoin('pet as p', 'p.idpet', '=', 'td.idpet')
            ->leftJoin('pemilik as pm', 'pm.idpemilik', '=', 'p.idpemilik')
            ->leftJoin('user as u_pemilik', 'u_pemilik.iduser', '=', 'pm.iduser') // User untuk pemilik
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser') // User untuk dokter
            ->whereDate('td.waktu_daftar', $selectedDate)
            ->orderBy('td.no_urut', 'asc')
            ->get();

        // Get available pets (yang belum ada antrian aktif di tanggal yang dipilih)
        $activePetIds = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $selectedDaftarDate)
            ->where('status', 0)
            ->pluck('idpet')
            ->toArray();

        $pets = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])
            ->whereNotIn('idpet', $activePetIds)
            ->get();

        // Get available doctors
        $doctors = RoleUser::with(['user', 'role'])
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Dokter');
            })
            ->where('status', 1)
            ->get();

        return view('rshp.resepsionis.temu-dokter.index', compact(
            'antrian',
            'pets',
            'doctors',
            'selectedDate',
            'selectedDaftarDate'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'idrole_user' => 'required|exists:role_user,idrole_user',
            'tanggal_daftar' => 'required|date|after_or_equal:today'
        ]);

        try {
            $idpet = $request->idpet;
            $idrole_user = $request->idrole_user;
            $tanggal_daftar = $request->tanggal_daftar;

            // Cek apakah pet sudah ada antrian aktif di tanggal yang sama
            $existing = DB::table('temu_dokter')
                ->where('idpet', $idpet)
                ->where('status', 0)
                ->whereDate('waktu_daftar', $tanggal_daftar)
                ->exists();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Pet sudah berada dalam antrian pada tanggal ' . date('d-m-Y', strtotime($tanggal_daftar)))
                    ->withInput();
            }

            // Get next queue number
            $nextNo = DB::table('temu_dokter')
                ->whereDate('waktu_daftar', $tanggal_daftar)
                ->where('idrole_user', $idrole_user)
                ->max('no_urut') + 1;

            // Create antrian
            $temuDokterId = DB::table('temu_dokter')->insertGetId([
                'idpet' => $idpet,
                'idrole_user' => $idrole_user,
                'no_urut' => $nextNo,
                'waktu_daftar' => $tanggal_daftar . ' ' . date('H:i:s'),
                'status' => 0,
            ]);

            return redirect()->route('resepsionis.temu-dokter.index', [
                'date' => $tanggal_daftar,
                'tanggal_daftar' => $tanggal_daftar
            ])->with('success', 'Antrian berhasil dibuat! Nomor antrian: ' . $nextNo);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat antrian: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'idreservasi_dokter' => 'required|exists:temu_dokter,idreservasi_dokter',
            'status' => 'required|in:1,2' // 1: Selesai, 2: Batal
        ]);

        try {
            $updated = DB::table('temu_dokter')
                ->where('idreservasi_dokter', $request->idreservasi_dokter)
                ->where('status', 0) // Hanya bisa update yang masih antri
                ->update([
                    'status' => $request->status,
                ]);

            if ($updated) {
                $statusText = $request->status == 1 ? 'selesai' : 'dibatalkan';
                return redirect()->back()
                    ->with('success', 'Status antrian berhasil diubah menjadi ' . $statusText);
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal mengubah status antrian. Antrian mungkin sudah diproses.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
    // Di Controller TemuDokterController

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Jika memerlukan halaman create terpisah
        $selectedDate = request('date', date('Y-m-d'));
        $selectedDaftarDate = request('tanggal_daftar', date('Y-m-d'));

        // Ambil data pets yang tersedia
        $pets = Pet::with('pemilik.user')
            ->whereDoesntHave('reservasiDokter', function ($query) use ($selectedDaftarDate) {
                $query->whereDate('tanggal_daftar', $selectedDaftarDate)
                    ->whereIn('status', [0, 1]); // Hanya yang belum selesai atau batal
            })
            ->get();

        // Ambil data dokter
        $doctors = RoleUser::with('user')
            ->where('idrole', 2) // ID untuk dokter
            ->where('status', 1)
            ->get();

        // Ambil antrian untuk tanggal yang dipilih
        $antrian = ReservasiDokter::with(['pet', 'dokter.user', 'pet.pemilik.user'])
            ->whereDate('tanggal_daftar', $selectedDate)
            ->orderBy('no_urut')
            ->get();

        return view('resepsionis.temu-dokter.create', compact(
            'pets',
            'doctors',
            'antrian',
            'selectedDate',
            'selectedDaftarDate'
        ));
    }
    // Di Controller TemuDokterController

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reservasi = ReservasiDokter::with(['pet', 'dokter.user', 'pet.pemilik.user'])
            ->findOrFail($id);

        $selectedDate = request('date', date('Y-m-d'));
        $selectedDaftarDate = request('tanggal_daftar', $reservasi->tanggal_daftar);

        // Ambil data pets yang tersedia
        $pets = Pet::with('pemilik.user')
            ->whereDoesntHave('reservasiDokter', function ($query) use ($selectedDaftarDate, $id) {
                $query->whereDate('tanggal_daftar', $selectedDaftarDate)
                    ->whereIn('status', [0, 1])
                    ->where('idreservasi_dokter', '!=', $id);
            })
            ->get();

        // Ambil data dokter
        $doctors = RoleUser::with('user')
            ->where('idrole', 2)
            ->where('status', 1)
            ->get();

        return view('resepsionis.temu-dokter.edit', compact(
            'reservasi',
            'pets',
            'doctors',
            'selectedDate',
            'selectedDaftarDate'
        ));
    }
}
