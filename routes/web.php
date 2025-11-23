<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\rshp\rshpController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\JenisHewanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\KodeTindakanTerapiController;
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PemilikController;
use App\Http\Controllers\Admin\PetController;

use App\Http\Controllers\Dokter\DashboardDokterController;
use App\Http\Controllers\Dokter\RekamMedisDocController;
use App\Http\Controllers\Dokter\RekamMedisListController;

use App\Http\Controllers\Perawat\DashboardPerawatController;
use App\Http\Controllers\Perawat\RekamMedisPerController;

use App\Http\Controllers\Pemilik\DashboardPemilikController;
use App\Http\Controllers\Pemilik\PetListController;
use App\Http\Controllers\Pemilik\ReservasiListController;
use App\Http\Controllers\Pemilik\RekamMedisPemController;

use App\Http\Controllers\Resepsionis\DashboardResepsionisController;
use App\Http\Controllers\Resepsionis\RegistrasiPemilikController;
use App\Http\Controllers\Resepsionis\RegistrasiPetController;
use App\Http\Controllers\Resepsionis\TemuDokterController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Cek koneksi database
Route::get('/cek-koneksi', [rshpController::class, 'cekKoneksi'])->name('site.cek-koneksi');

// Halaman utama
Route::get('/', [rshpController::class, 'index'])->name('home');
Route::get('/struktur', [rshpController::class, 'struktur'])->name('struktur');
Route::get('/layanan', [rshpController::class, 'layanan'])->name('layanan');
Route::get('/visi-misi', [rshpController::class, 'visiMisi'])->name('visi-misi');

// Authentication Routes (Laravel default)
Auth::routes();

// Temporary routes untuk debugging
Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Logout berhasil');
});

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return 'User sedang login: ' . Auth::user()->email;
    }
    return 'User belum login';
});

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['isAdministrator'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    // Data Master Routes
    Route::prefix('datamaster')->group(function () {

        // User
        Route::resource('user', UserController::class)->names([
            'index' => 'admin.user.index',
            'create' => 'admin.user.create',
            'store' => 'admin.user.store',
            'edit' => 'admin.user.edit',
            'update' => 'admin.user.update',
            'destroy' => 'admin.user.destroy',
        ]);

        // ✅ TAMBAHKAN ROUTE INI - Reset Password
        Route::post('user/{id}/reset-password', [UserController::class, 'resetPassword'])
            ->name('admin.user.reset-password');


        // Pemilik
        Route::resource('pemilik', PemilikController::class)->names([
            'index' => 'admin.pemilik.index',
            'create' => 'admin.pemilik.create',
            'store' => 'admin.pemilik.store',
            'edit' => 'admin.pemilik.edit',
            'update' => 'admin.pemilik.update',
            'destroy' => 'admin.pemilik.destroy',
        ]);

        // Pet
        Route::resource('pet', PetController::class)->names([
            'index' => 'admin.pet.index',
            'create' => 'admin.pet.create',
            'store' => 'admin.pet.store',
            'edit' => 'admin.pet.edit',
            'update' => 'admin.pet.update',
            'destroy' => 'admin.pet.destroy',
        ]);

        // Jenis Hewan
        Route::resource('jenis-hewan', JenisHewanController::class)->names([
            'index' => 'admin.jenis-hewan.index',
            'create' => 'admin.jenis-hewan.create',
            'store' => 'admin.jenis-hewan.store',
            'edit' => 'admin.jenis-hewan.edit',
            'update' => 'admin.jenis-hewan.update',
            'destroy' => 'admin.jenis-hewan.destroy',
        ]);

        // Ras Hewan
        Route::resource('ras-hewan', RasHewanController::class)->names([
            'index' => 'admin.ras-hewan.index',
            'create' => 'admin.ras-hewan.create',
            'store' => 'admin.ras-hewan.store',
            'edit' => 'admin.ras-hewan.edit',
            'update' => 'admin.ras-hewan.update',
            'destroy' => 'admin.ras-hewan.destroy',
        ]);

        // Role
        Route::resource('role', RoleController::class)->names([
            'index' => 'admin.role.index',
            'create' => 'admin.role.create',
            'store' => 'admin.role.store',
            'edit' => 'admin.role.edit',
            'update' => 'admin.role.update',
            'destroy' => 'admin.role.destroy',
        ]);

        // Kategori
        Route::resource('kategori', KategoriController::class)->names([
            'index' => 'admin.kategori.index',
            'create' => 'admin.kategori.create',
            'store' => 'admin.kategori.store',
            'edit' => 'admin.kategori.edit',
            'update' => 'admin.kategori.update',
            'destroy' => 'admin.kategori.destroy',
        ]);

        // Kategori Klinis (pakai alias kk di URL)
        Route::resource('kk', KategoriKlinisController::class)->names([
            'index' => 'admin.kategori-klinis.index',
            'create' => 'admin.kategori-klinis.create',
            'store' => 'admin.kategori-klinis.store',
            'edit' => 'admin.kategori-klinis.edit',
            'update' => 'admin.kategori-klinis.update',
            'destroy' => 'admin.kategori-klinis.destroy',
        ]);

        // Kode Tindakan Terapi (pakai alias ktt di URL)
        Route::resource('ktt', KodeTindakanTerapiController::class)->names([
            'index' => 'admin.kode-tindakan-terapi.index',
            'create' => 'admin.kode-tindakan-terapi.create',
            'store' => 'admin.kode-tindakan-terapi.store',
            'edit' => 'admin.kode-tindakan-terapi.edit',
            'update' => 'admin.kode-tindakan-terapi.update',
            'destroy' => 'admin.kode-tindakan-terapi.destroy',
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Resepsionis Routes - DIPERBAIKI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'isResepsionis'])->prefix('resepsionis')->name('resepsionis.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardResepsionisController::class, 'index'])->name('dashboard');

    // ===== PEMILIK ROUTES =====
    Route::prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/', [RegistrasiPemilikController::class, 'index'])->name('index');
        Route::get('/create', [RegistrasiPemilikController::class, 'create'])->name('create');
        Route::post('/', [RegistrasiPemilikController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RegistrasiPemilikController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RegistrasiPemilikController::class, 'update'])->name('update');
        Route::delete('/{id}', [RegistrasiPemilikController::class, 'destroy'])->name('destroy');
    });

    // ===== PET ROUTES =====
    Route::prefix('pet')->name('pet.')->group(function () {
        Route::get('/', [RegistrasiPetController::class, 'index'])->name('index');
        Route::get('/create', [RegistrasiPetController::class, 'create'])->name('create');
        Route::post('/', [RegistrasiPetController::class, 'store'])->name('store');
        Route::get('/{id}', [RegistrasiPetController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RegistrasiPetController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RegistrasiPetController::class, 'update'])->name('update');
        Route::delete('/{id}', [RegistrasiPetController::class, 'destroy'])->name('destroy');
    });

    // ===== TEMU DOKTER ROUTES =====
    Route::prefix('temu-dokter')->name('temu-dokter.')->group(function () {
        Route::get('/', [TemuDokterController::class, 'index'])->name('index');
        Route::post('/', [TemuDokterController::class, 'store'])->name('store');
        Route::post('/update-status', [TemuDokterController::class, 'updateStatus'])->name('update-status');
    });

    // ===== BACKWARD COMPATIBILITY (OPSIONAL) =====
    // Hapus route duplikat di bawah ini jika tidak diperlukan
    Route::get('/registrasi-pemilik', [RegistrasiPemilikController::class, 'index'])->name('registrasi.pemilik');
    Route::post('/registrasi-pemilik', [RegistrasiPemilikController::class, 'store'])->name('registrasi.pemilik.store');

    Route::get('/registrasi-pet', [RegistrasiPetController::class, 'index'])->name('registrasi.pet');
    Route::get('/registrasi-pet/create', [RegistrasiPetController::class, 'create'])->name('registrasi.pet.create');
    Route::post('/registrasi-pet', [RegistrasiPetController::class, 'store'])->name('registrasi.pet.store');
});

/*
|--------------------------------------------------------------------------
| Dokter Routes - Sesuai dengan Native PHP
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'isDokter'])->prefix('dokter')->name('dokter.')->group(function () {

    // Dashboard (sesuai home-dokter.php)
    Route::get('/dashboard', [DashboardDokterController::class, 'index'])->name('dashboard');

    // Data Pasien
    Route::get('/data-pasien', [DashboardDokterController::class, 'dataPasien'])->name('data-pasien');

    // Rekam Medis - List & Detail (sesuai rekam-medis-list.php dan rekam-medis-detail-doc.php)
    Route::get('/rekam-medis', [RekamMedisListController::class, 'index'])->name('rekam-medis.list');
    Route::get('/rekam-medis/{id}', [RekamMedisListController::class, 'show'])->name('rekam-medis.show');

    // CRUD Detail Rekam Medis - Dipindah ke DashboardDokterController
    Route::get('/rekam-medis/{idRekamMedis}/detail/create', [DashboardDokterController::class, 'detailRekamMedisCreate'])
        ->name('detail-rekam-medis.create');
    Route::post('/rekam-medis/{idRekamMedis}/detail', [DashboardDokterController::class, 'detailRekamMedisStore'])
        ->name('detail-rekam-medis.store');
    Route::get('/rekam-medis/{idRekamMedis}/detail/{idDetail}/edit', [DashboardDokterController::class, 'detailRekamMedisEdit'])
        ->name('detail-rekam-medis.edit');
    Route::put('/rekam-medis/{idRekamMedis}/detail/{idDetail}', [DashboardDokterController::class, 'detailRekamMedisUpdate'])
        ->name('detail-rekam-medis.update');
    Route::delete('/rekam-medis/{idRekamMedis}/detail/{idDetail}', [DashboardDokterController::class, 'detailRekamMedisDestroy'])
        ->name('detail-rekam-medis.destroy');

    // Selesai Rekam Medis
    Route::post('/rekam-medis/{id}/selesai', [DashboardDokterController::class, 'selesaiRekamMedis'])
        ->name('rekam-medis.selesai');

    // Profil
    Route::get('/profil', [DashboardDokterController::class, 'profil'])->name('profil');
});

/*
|--------------------------------------------------------------------------
| Perawat Routes - DIPERBAIKI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'isPerawat'])->prefix('perawat')->name('perawat.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardPerawatController::class, 'index'])->name('dashboard');

    // Data Pasien (view data pasien)
    Route::get('/data-pasien', [DashboardPerawatController::class, 'dataPasien'])->name('data-pasien');

    // Rekam Medis Routes
    Route::get('/rekam-medis', [RekamMedisPerController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/create/{idReservasi}/{idPet}', [RekamMedisPerController::class, 'create'])->name('rekam-medis.create');
    Route::post('/rekam-medis', [RekamMedisPerController::class, 'store'])->name('rekam-medis.store');
    Route::get('/rekam-medis/{id}', [RekamMedisPerController::class, 'detail'])->name('rekam-medis.detail');
    Route::post('/rekam-medis/{id}/header', [RekamMedisPerController::class, 'updateHeader'])->name('rekam-medis.update-header');
    Route::post('/rekam-medis/{id}/detail', [RekamMedisPerController::class, 'createDetail'])->name('rekam-medis.create-detail');
    Route::put('/rekam-medis/{id}/detail/{idDetail}', [RekamMedisPerController::class, 'updateDetail'])->name('rekam-medis.update-detail');
    Route::delete('/rekam-medis/{id}/detail/{idDetail}', [RekamMedisPerController::class, 'deleteDetail'])->name('rekam-medis.delete-detail');

    // Pasien Hari Ini
    Route::get('/pasien-hari-ini', [DashboardPerawatController::class, 'pasienHariIni'])->name('pasien-hari-ini');

    // Tindakan
    Route::get('/tindakan', [DashboardPerawatController::class, 'tindakan'])->name('tindakan');
    Route::get('/tindakan/create/{id}', [DashboardPerawatController::class, 'tindakanCreate'])->name('tindakan.create');

    // Profil
    Route::get('/profil', [DashboardPerawatController::class, 'profil'])->name('profil');
});

/*
|--------------------------------------------------------------------------
| Pemilik Routes - DIPERBAIKI
|--------------------------------------------------------------------------
*/

// Pemilik Routes - DIPERBAIKI
Route::middleware(['auth', 'isPemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardPemilikController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardPemilikController::class, 'index'])->name('home');

    // Pet (view pet yang dimiliki)
    Route::get('/pet', [PetListController::class, 'index'])->name('pet.list');
    Route::get('/pet/{id}', [PetListController::class, 'show'])->name('pet.show');

    // Rekam Medis (view rekam medis)
    Route::get('/rekammedis', [RekamMedisPemController::class, 'index'])->name('rekammedis.list');
    Route::get('/rekammedis/{id}', [RekamMedisPemController::class, 'show'])->name('rekammedis.show');

    // Reservasi (view jadwal temu dokter)
    Route::get('/reservasi', [ReservasiListController::class, 'index'])->name('reservasi.list');
    Route::post('/reservasi', [ReservasiListController::class, 'store'])->name('reservasi.store');

    // ✅ TAMBAHKAN ROUTE INI - Batalkan Reservasi
    Route::post('/reservasi/{id}/cancel', [ReservasiListController::class, 'cancel'])->name('reservasi.cancel');

    // Profil
    Route::get('/profil', [DashboardPemilikController::class, 'profil'])->name('profil');
});
