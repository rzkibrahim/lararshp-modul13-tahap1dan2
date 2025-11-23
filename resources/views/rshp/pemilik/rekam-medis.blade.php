@extends('layouts.lte.pemilik')

@section('title', 'Rekam Medis - RSHP')

@section('page_title', 'Rekam Medis Hewan Peliharaan')
@section('page_description', 'Riwayat kesehatan dan perawatan hewan peliharaan Anda')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Rekam Medis</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $rekamMedisList->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-file-medical text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ $rekamMedisList->where('created_at', '>=', now()->startOfMonth())->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Hewan Dirawat</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $rekamMedisList->unique('idpet')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-paw text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Dokter</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $rekamMedisList->unique('dokter_pemeriksa')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-md text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekam Medis Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-medical text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar Rekam Medis</h3>
                        <p class="text-sm text-gray-600">Riwayat pemeriksaan kesehatan hewan peliharaan</p>
                    </div>
                </div>
            </div>
        </div>

        @if($rekamMedisList->isEmpty())
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-file-medical text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Rekam Medis</h3>
            <p class="text-gray-500 mb-4">Belum ada riwayat pemeriksaan kesehatan untuk hewan peliharaan Anda</p>
            <a href="{{ route('pemilik.reservasi.list') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-calendar-plus mr-2"></i>
                Buat Reservasi Baru
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Nama Hewan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jenis & Ras
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Diagnosa
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Dokter
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rekamMedisList as $rekamMedis)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($rekamMedis->nama_pet, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $rekamMedis->nama_pet }}</div>
                                    <div class="text-xs text-gray-500">ID: #{{ $rekamMedis->idpet }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $rekamMedis->nama_ras ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $rekamMedis->nama_jenis_hewan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $rekamMedis->diagnosa }}">
                                {{ $rekamMedis->diagnosa ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-green-600 text-xs"></i>
                                </div>
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $rekamMedis->nama_dokter ?? 'Belum Ada' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('pemilik.rekammedis.show', $rekamMedis->idrekam_medis) }}" 
                               class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <i class="fas fa-eye mr-2"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Info Card --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Informasi Rekam Medis</h4>
                <ul class="text-gray-700 text-sm space-y-1">
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                        <span>Rekam medis mencatat semua riwayat pemeriksaan dan perawatan hewan Anda</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                        <span>Klik tombol <strong>Detail</strong> untuk melihat informasi lengkap termasuk tindakan dan terapi</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                        <span>Rekam medis dibuat setelah pemeriksaan oleh dokter selesai dilakukan</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection