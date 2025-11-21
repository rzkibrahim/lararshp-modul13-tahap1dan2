@extends('layouts.lte.pemilik')

@section('title', 'Dashboard Pemilik - RSHP')
@section('page_title', 'Dashboard Pemilik')
@section('page_description', 'Selamat datang di dashboard pemilik')

@section('content')
<div class="space-y-6">
    {{-- Welcome Message --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-2 text-gray-800">Selamat Datang, {{ session('user_name', 'Pemilik') }}!</h2>
        <p class="text-gray-600">Kelola hewan peliharaan dan pantau kesehatan mereka dengan mudah.</p>
    </div>

    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1: Total Pet --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Total Pet</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalPet ?? 0 }}</p>
                </div>
                <i class="fas fa-paw text-blue-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Hewan peliharaan aktif</p>
        </div>

        {{-- Card 2: Total Kunjungan --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Total Kunjungan</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalKunjungan ?? 0 }}</p>
                </div>
                <i class="fas fa-calendar-check text-green-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Semua waktu</p>
        </div>

        {{-- Card 3: Kunjungan Bulan Ini --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-orange-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Kunjungan Bulan Ini</h3>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $kunjunganBulanIni ?? 0 }}</p>
                </div>
                <i class="fas fa-calendar-alt text-orange-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Bulan {{ date('F Y') }}</p>
        </div>

        {{-- Card 4: Menunggu Antrian --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Menunggu Antrian</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $menungguAntrian ?? 0 }}</p>
                </div>
                <i class="fas fa-clock text-purple-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Reservasi aktif</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('pemilik.pet.list') }}"
            class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 border border-blue-200 hover:border-blue-300 card-hover">
            <div class="text-center">
                <i class="fas fa-paw text-blue-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Data Pet</h3>
                <p class="text-sm text-gray-500 mt-2">Kelola hewan peliharaan</p>
            </div>
        </a>

        <a href="{{ route('pemilik.reservasi.list') }}"
            class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 border border-green-200 hover:border-green-300 card-hover">
            <div class="text-center">
                <i class="fas fa-calendar-plus text-green-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Buat Reservasi</h3>
                <p class="text-sm text-gray-500 mt-2">Jadwalkan kunjungan</p>
            </div>
        </a>

        <a href="{{ route('pemilik.rekammedis.list') }}"
            class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 border border-orange-200 hover:border-orange-300 card-hover">
            <div class="text-center">
                <i class="fas fa-file-medical text-orange-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Rekam Medis</h3>
                <p class="text-sm text-gray-500 mt-2">Lihat riwayat kesehatan</p>
            </div>
        </a>
    </div>

    {{-- Pet List --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Pet Saya</h3>
            <a href="{{ route('pemilik.pet.list') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua →
            </a>
        </div>

        @if($listPet->count() === 0)
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-paw text-4xl mb-3"></i>
            <p>Belum ada pet terdaftar.</p>
            <a href="{{ route('pemilik.reservasi.list') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Daftarkan pet pertama Anda →
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Pet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Jenis</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Ras</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($listPet->take(5) as $pet)
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $pet->nama }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pet->nama_jenis_hewan ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pet->nama_ras ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($pet->jenis_kelamin == 'M')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Jantan</span>
                            @elseif($pet->jenis_kelamin == 'F')
                                <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs">Betina</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('pemilik.reservasi.list') }}?pet={{ $pet->idpet }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Reservasi
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Recent History --}}
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Riwayat Kunjungan Terbaru</h3>
            <a href="{{ route('pemilik.rekammedis.list') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua →
            </a>
        </div>

        @if($riwayatKunjungan->count() === 0)
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-history text-4xl mb-3"></i>
            <p>Belum ada riwayat kunjungan.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Pet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dokter</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($riwayatKunjungan as $riwayat)
                    @php
                    $statusClass = match($riwayat->status) {
                        0 => 'bg-yellow-100 text-yellow-800',
                        1 => 'bg-green-100 text-green-800',
                        2 => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };

                    $statusText = match($riwayat->status) {
                        0 => 'Menunggu',
                        1 => 'Selesai',
                        2 => 'Batal',
                        default => 'Tidak Diketahui'
                    };
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($riwayat->waktu_daftar)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $riwayat->nama_pet }}</td>
                        <td class="px-4 py-3 text-sm">{{ $riwayat->nama_dokter ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection