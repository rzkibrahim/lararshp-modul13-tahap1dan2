@extends('layouts.lte.pemilik')

@section('title', 'Daftar Hewan Peliharaan - RSHP')

@section('page_title', 'Daftar Hewan Peliharaan')
@section('page_description', 'Kelola dan lihat informasi hewan peliharaan Anda')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Hewan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pets->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-paw text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Jenis Hewan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pets->unique('nama_jenis_hewan')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-list text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Ras Berbeda</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pets->unique('nama_ras')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-dna text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Pets Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-paw text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar Hewan Peliharaan</h3>
                        <p class="text-sm text-gray-600">Total {{ $pets->count() }} hewan terdaftar</p>
                    </div>
                </div>
            </div>
        </div>

        @if($pets->isEmpty())
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-paw text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Hewan Peliharaan</h3>
            <p class="text-gray-500">Anda belum memiliki hewan peliharaan yang terdaftar</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Nama Hewan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jenis & Ras
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Tanggal Lahir
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jenis Kelamin
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Warna/Tanda
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pets as $pet)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($pet->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $pet->nama }}</div>
                                    <div class="text-xs text-gray-500">ID: #{{ $pet->idpet }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $pet->nama_ras ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $pet->nama_jenis_hewan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $pet->tanggal_lahir ? \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d M Y') : '-' }}
                            </div>
                            @if($pet->tanggal_lahir)
                            <div class="text-xs text-gray-500">
                                Umur: {{ \Carbon\Carbon::parse($pet->tanggal_lahir)->diffInYears(now()) }} tahun
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pet->jenis_kelamin == 'M')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-mars mr-1.5"></i> Jantan
                            </span>
                            @elseif($pet->jenis_kelamin == 'F')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                <i class="fas fa-venus mr-1.5"></i> Betina
                            </span>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $pet->warna_tanda ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('pemilik.pet.show', $pet->idpet) }}" 
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
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Informasi</h4>
                <p class="text-gray-700 text-sm leading-relaxed">
                    Untuk melihat riwayat kunjungan dan rekam medis lengkap dari setiap hewan peliharaan Anda, 
                    klik tombol <strong>Detail</strong> pada hewan yang ingin dilihat.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection