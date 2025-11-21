@extends('layouts.lte.resepsionis')

@section('title', 'Dashboard Resepsionis - RSHP')
@section('page_title', 'Dashboard Resepsionis')
@section('page_description', 'Selamat datang di dashboard resepsionis')

@section('content')
<div class="space-y-6">
    {{-- Dashboard Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1: Total Antrian Hari Ini --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-primary-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Total Antrian Hari Ini</h3>
                    <p class="text-3xl font-bold text-primary-600 mt-2">{{ $totalAntrianHariIni }}</p>
                </div>
                <i class="fas fa-list-ol text-primary-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Tanggal: {{ date('d/m/Y') }}</p>
        </div>

        {{-- Card 2: Pemilik Aktif --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Pemilik Aktif</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $pemilikAktifHariIni }}</p>
                </div>
                <i class="fas fa-users text-green-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Kunjungan hari ini</p>
        </div>

        {{-- Card 3: Sedang Diperiksa --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Menunggu</h3>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $antrianMenunggu }}</p>
                </div>
                <i class="fas fa-clock text-orange-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Dalam antrian</p>
        </div>

        {{-- Card 4: Total Pet --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-semibold">Total Pet</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalPet }}</p>
                </div>
                <i class="fas fa-paw text-purple-500 text-2xl"></i>
            </div>
            <p class="text-xs text-gray-500 mt-2">Terdaftar di sistem</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('resepsionis.registrasi.pemilik') }}"
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-primary-200 hover:border-primary-300 card-hover">
            <div class="text-center">
                <i class="fas fa-user-plus text-primary-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Registrasi Pemilik Baru</h3>
                <p class="text-sm text-gray-500 mt-2">Tambah data pemilik hewan baru</p>
            </div>
        </a>

        <a href="{{ route('resepsionis.registrasi.pet') }}"
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-green-200 hover:border-green-300 card-hover">
            <div class="text-center">
                <i class="fas fa-paw text-green-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Registrasi Pet Baru</h3>
                <p class="text-sm text-gray-500 mt-2">Tambah data hewan peliharaan</p>
            </div>
        </a>

        <a href="{{ route('resepsionis.temu-dokter.index') }}"
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-orange-200 hover:border-orange-300 card-hover">
            <div class="text-center">
                <i class="fas fa-calendar-plus text-orange-500 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-700">Kelola Antrian</h3>
                <p class="text-sm text-gray-500 mt-2">Kelola temu dokter & antrian</p>
            </div>
        </a>
    </div>

    {{-- Antrian Hari Ini --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Antrian Hari Ini</h3>
            <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ date('d F Y') }}
            </span>
        </div>

        @if($antrianHariIni->count() === 0)
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-calendar-times text-4xl mb-3"></i>
            <p>Tidak ada antrian hari ini.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No. Antrian</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Pet</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pemilik</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Waktu Daftar</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($antrianHariIni as $antrian)
                    @php
                    $statusClass = match($antrian->status) {
                        0 => 'bg-yellow-100 text-yellow-800',
                        1 => 'bg-green-100 text-green-800',
                        2 => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };

                    $statusText = match($antrian->status) {
                        0 => 'Menunggu',
                        1 => 'Selesai',
                        2 => 'Batal',
                        default => 'Tidak Diketahui'
                    };
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $antrian->no_urut }}</td>
                        <td class="px-4 py-3 text-sm">{{ $antrian->nama_pet }}</td>
                        <td class="px-4 py-3 text-sm">{{ $antrian->nama_pemilik }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('H:i') }}
                        </td>
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

    {{-- Info Section --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-bold mb-4">Informasi Akun Resepsionis</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600">Nama:</p>
                <p class="font-semibold">{{ session('user_name', 'Resepsionis') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Email:</p>
                <p class="font-semibold">{{ session('user_email', 'resepsionis@mail.com') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Role:</p>
                <p class="font-semibold">{{ session('user_role_name', 'Resepsionis') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Shift:</p>
                <p class="font-semibold text-green-600">Pagi (08:00 - 16:00)</p>
            </div>
        </div>
    </div>
</div>
@endsection