@extends('layouts.lte.perawat')

@section('title', 'Dashboard Perawat - RSHP')
@section('page_title', 'Dashboard Perawat')
@section('page_description', 'Selamat datang di dashboard perawat')

@section('content')
<div class="space-y-6">
    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1: Pasien Hari Ini --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-primary-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Pasien Hari Ini</h3>
                    <p class="text-3xl font-bold text-primary-600 mt-2">{{ $totalPasienHariIni }}</p>
                </div>
                <i class="fas fa-user-injured text-primary-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Tanggal: {{ date('d/m/Y') }}</p>
        </div>

        {{-- Card 2: Tindakan Hari Ini --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Tindakan Hari Ini</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $tindakanHariIni }}</p>
                </div>
                <i class="fas fa-syringe text-yellow-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Tindakan dilakukan</p>
        </div>

        {{-- Card 3: Menunggu Perawatan --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Menunggu Perawatan</h3>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $menungguPerawatan }}</p>
                </div>
                <i class="fas fa-clock text-orange-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Dalam antrian</p>
        </div>

        {{-- Card 4: Total Perawatan --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Total Perawatan</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalPerawatan }}</p>
                </div>
                <i class="fas fa-hand-holding-medical text-green-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Semua waktu</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('perawat.rekam-medis.index') }}"
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-primary-200 hover:border-primary-300 card-hover">
            <div class="text-center">
                <i class="fas fa-file-medical text-primary-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Rekam Medis</h3>
                <p class="text-sm text-gray-500 mt-2">Kelola rekam medis pasien</p>
            </div>
        </a>

        <a href="{{ route('perawat.rekam-medis.index') }}"
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-blue-200 hover:border-blue-300 card-hover">
            <div class="text-center">
                <i class="fas fa-list text-blue-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Pasien Hari Ini</h3>
                <p class="text-sm text-gray-500 mt-2">Lihat daftar pasien</p>
            </div>
        </a>

        <a href="{{ route('perawat.rekam-medis.index') }}"
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-orange-200 hover:border-orange-300 card-hover">
            <div class="text-center">
                <i class="fas fa-procedures text-orange-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Tindakan</h3>
                <p class="text-sm text-gray-500 mt-2">Input tindakan perawat</p>
            </div>
        </a>
    </div>

    {{-- Pasien Hari Ini --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Pasien Hari Ini</h3>
            <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ date('d F Y') }}
            </span>
        </div>

        @if($pasienHariIni->count() === 0)
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-user-times text-4xl mb-3"></i>
            <p>Tidak ada pasien hari ini.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No. Antrian</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Pet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pemilik</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Jenis Hewan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pasienHariIni as $pasien)
                    @php
                    $statusClass = match($pasien->status) {
                        0 => 'bg-yellow-100 text-yellow-800',
                        1 => 'bg-green-100 text-green-800',
                        2 => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };

                    $statusText = match($pasien->status) {
                        0 => 'Menunggu',
                        1 => 'Selesai',
                        2 => 'Batal',
                        default => 'Tidak Diketahui'
                    };
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $pasien->no_urut }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pasien->nama_pet }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pasien->nama_pemilik }}</td>
                        <td class="px-4 py-3 text-sm">{{ $pasien->nama_jenis_hewan }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($pasien->status == 0)
                            <a href="{{ route('perawat.tindakan.create', $pasien->idreservasi_dokter) }}" 
                               class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                Rawat
                            </a>
                            @elseif($pasien->status == 1)
                            <span class="text-green-600 text-sm">Selesai</span>
                            @else
                            <span class="text-red-600 text-sm">Dibatalkan</span>
                            @endif
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