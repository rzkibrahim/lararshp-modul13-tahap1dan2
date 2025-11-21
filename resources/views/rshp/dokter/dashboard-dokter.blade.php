@extends('layouts.lte.dokter')

@section('title', 'Dashboard Dokter')
@section('page_title', 'Dashboard')
@section('page_description', 'Ringkasan aktivitas hari ini')

@section('content')
<div class="space-y-6">
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pasien Hari Ini -->
        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg overflow-hidden card-hover">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Pasien Hari Ini</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalPasienHariIni }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <a href="#" class="text-blue-100 hover:text-white text-sm font-medium flex items-center group">
                        Lihat detail
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sedang Diperiksa -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg overflow-hidden card-hover">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Sedang Diperiksa</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $sedangDiperiksa }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-stethoscope text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <a href="#" class="text-green-100 hover:text-white text-sm font-medium flex items-center group">
                        Lihat detail
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Antrian Menunggu -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-lg overflow-hidden card-hover">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Antrian Menunggu</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $antrianMenunggu }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <a href="#" class="text-orange-100 hover:text-white text-sm font-medium flex items-center group">
                        Lihat detail
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Rekam Medis -->
        <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl shadow-lg overflow-hidden card-hover">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-medium">Total Rekam Medis</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalRekamMedis }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-file-medical text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <a href="{{ route('dokter.rekam-medis.list') }}" class="text-pink-100 hover:text-white text-sm font-medium flex items-center group">
                        Lihat detail
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Antrian Hari Ini -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Antrian Hari Ini</h3>
                    <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $antrianHariIni->count() }} Pasien
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($antrianHariIni as $antrian)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">
                                    {{ $antrian->no_urut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $antrian->nama_pet }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $antrian->nama_pemilik }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $antrian->jenis_hewan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($antrian->status == 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Menunggu
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Selesai
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($antrianHariIni->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-users-slash text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada antrian hari ini</p>
                <p class="text-gray-400 text-sm mt-2">Semua pasien telah ditangani</p>
            </div>
            @endif
        </div>

        <!-- Rekam Medis Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Rekam Medis Terbaru</h3>
                    <div class="flex items-center space-x-2">
                        <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $rekamMedisTerbaru->count() }} Records
                        </span>
                        <a href="{{ route('dokter.rekam-medis.list') }}"
                            class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
                            <i class="fas fa-list"></i>
                            <span>Lihat Semua</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rekamMedisTerbaru as $rm)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($rm->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-paw text-primary-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $rm->nama_pet }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="max-w-xs truncate" title="{{ $rm->diagnosa }}">
                                    {{ $rm->diagnosa ? Str::limit($rm->diagnosa, 40) : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('dokter.rekam-medis.show', $rm->idrekam_medis) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
                                    <i class="fas fa-eye"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($rekamMedisTerbaru->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-file-medical-alt text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada rekam medis</p>
                <p class="text-gray-400 text-sm mt-2">Rekam medis akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            <p class="text-gray-500 text-sm mt-1">Akses cepat ke fitur utama</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Rekam Medis -->
                <a href="{{ route('dokter.rekam-medis.list') }}"
                    class="group p-6 bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-200 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-medical text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Rekam Medis</h4>
                        <p class="text-gray-600 text-sm">Kelola data rekam medis pasien</p>
                    </div>
                </a>

                <!-- Data Pasien -->
                <a class="group p-6 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Data Pasien</h4>
                        <p class="text-gray-600 text-sm">Lihat data pasien dan hewan</p>
                    </div>
                </a>

                <!-- Profil -->
                <a class="group p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Profil</h4>
                        <p class="text-gray-600 text-sm">Kelola profil dokter</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection