@extends('layouts.lte.dokter')

@section('title', 'Daftar Rekam Medis - Dokter')
@section('page_title', 'Daftar Rekam Medis')
@section('page_description', 'Daftar semua rekam medis yang telah dibuat')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-xl font-semibold text-gray-900">Daftar Rekam Medis Terbaru</h5>
                    <p class="text-gray-500 text-sm mt-1">Semua rekam medis yang telah Anda buat</p>
                </div>
                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $rekamMedisList->count() }} Data
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            @if($rekamMedisList->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-file-medical-alt text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">Belum ada rekam medis yang didaftarkan</p>
                <p class="text-gray-400 text-sm mt-2">Rekam medis akan muncul di sini setelah Anda membuatnya</p>
            </div>
            @else
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID RM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Urut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pet</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Hewan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anamnesa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rekamMedisList as $rm)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-12 h-8 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                    #{{ $rm->idrekam_medis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">
                                    {{ $rm->no_urut ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($rm->created_at)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($rm->created_at)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-paw text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <!-- GUNAKAN PROPERTY LANGSUNG DARI QUERY -->
                                        <span class="text-sm font-medium text-gray-900 block">{{ $rm->nama_pet }}</span>
                                        <span class="text-xs text-gray-500">{{ $rm->nama_ras }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <!-- GUNAKAN PROPERTY LANGSUNG DARI QUERY -->
                                {{ $rm->nama_pemilik }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <!-- GUNAKAN PROPERTY LANGSUNG DARI QUERY -->
                                {{ $rm->nama_jenis_hewan }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    @if(!empty($rm->anamnesa))
                                    <span class="text-sm text-gray-600 truncate block" title="{{ $rm->anamnesa }}">
                                        {{ Str::limit($rm->anamnesa, 30) }}
                                    </span>
                                    @else
                                    <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    @if(!empty($rm->diagnosa))
                                    <span class="text-sm text-gray-600 truncate block" title="{{ $rm->diagnosa }}">
                                        {{ Str::limit($rm->diagnosa, 30) }}
                                    </span>
                                    @else
                                    <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('dokter.rekam-medis.show', $rm->idrekam_medis) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center space-x-1"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span>Detail</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <a href="{{ route('dokter.dashboard') }}"
                class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 px-6 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 w-fit">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>
</div>
@endsection