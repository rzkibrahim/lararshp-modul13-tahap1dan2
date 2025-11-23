@extends('layouts.lte.pemilik')

@section('title', 'Reservasi Temu Dokter - RSHP')

@section('page_title', 'Reservasi Temu Dokter')
@section('page_description', 'Kelola jadwal kunjungan hewan peliharaan Anda')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Reservasi</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $reservasiList->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Menunggu</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $reservasiList->where('status', 0)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Selesai</p>
                    <p class="text-3xl font-bold text-green-600">{{ $reservasiList->where('status', 1)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Dibatalkan</p>
                    <p class="text-3xl font-bold text-red-600">{{ $reservasiList->where('status', 2)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-times-circle text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Reservasi Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar Reservasi</h3>
                        <p class="text-sm text-gray-600">Jadwal temu dokter hewan peliharaan Anda</p>
                    </div>
                </div>
                <a href="{{ route('pemilik.reservasi.list') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Reservasi
                </a>
            </div>
        </div>

        @if($reservasiList->isEmpty())
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Reservasi</h3>
            <p class="text-gray-500 mb-4">Anda belum memiliki jadwal reservasi untuk pemeriksaan hewan peliharaan</p>
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
                            No. Urut
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Tanggal Kunjungan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Nama Hewan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Jenis & Ras
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Dokter
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reservasiList as $reservasi)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ $reservasi->no_urut }}
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Nomor Antrian</div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        #{{ str_pad($reservasi->no_urut, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($reservasi->waktu_daftar)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($reservasi->waktu_daftar)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($reservasi->nama_pet, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $reservasi->nama_pet }}</div>
                                    <div class="text-xs text-gray-500">ID: #{{ $reservasi->idpet }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $reservasi->nama_ras ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $reservasi->nama_jenis_hewan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($reservasi->nama_dokter)
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-md text-green-600 text-xs"></i>
                                </div>
                                <div class="text-sm text-gray-900 font-medium">{{ $reservasi->nama_dokter }}</div>
                            </div>
                            @else
                            <span class="text-sm text-gray-500 italic">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($reservasi->status == 0)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 shadow-sm">
                                <i class="fas fa-clock mr-1.5"></i> Menunggu
                            </span>
                            @elseif($reservasi->status == 1)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 shadow-sm">
                                <i class="fas fa-check-circle mr-1.5"></i> Selesai
                            </span>
                            @elseif($reservasi->status == 2)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 shadow-sm">
                                <i class="fas fa-times-circle mr-1.5"></i> Dibatalkan
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                @if($reservasi->status == 0)
                                <form action="{{ route('pemilik.reservasi.cancel', $reservasi->idreservasi_dokter) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                        <i class="fas fa-times mr-2"></i>
                                        Batalkan
                                    </button>
                                </form>
                                @elseif($reservasi->status == 1)
                                <span class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg">
                                    <i class="fas fa-check mr-2"></i>
                                    Sudah Selesai
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg">
                                    <i class="fas fa-ban mr-2"></i>
                                    Dibatalkan
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Status Reservasi</h4>
                    <ul class="text-gray-700 text-sm space-y-1">
                        <li class="flex items-start space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                Menunggu
                            </span>
                            <span>: Belum diperiksa dokter</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Selesai
                            </span>
                            <span>: Sudah diperiksa dokter</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                Dibatalkan
                            </span>
                            <span>: Reservasi dibatalkan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lightbulb text-green-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Tips Reservasi</h4>
                    <ul class="text-gray-700 text-sm space-y-1">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Datang 15 menit sebelum jadwal</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Bawa riwayat vaksinasi hewan</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <span>Batalkan jika tidak bisa hadir</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection