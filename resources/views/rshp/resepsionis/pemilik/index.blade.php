@extends('layouts.lte.resepsionis')

@section('title', 'Data Pemilik')
@section('page_title', 'Data Pemilik')
@section('page_description', 'Kelola data pemilik hewan peliharaan')

@section('content')
<div class="space-y-6">

    {{-- Card Data Pemilik --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Daftar Pemilik</h2>
                    <p class="text-primary-100 text-sm">Total: {{ $pemiliks->count() }} pemilik terdaftar</p>
                </div>
            </div>
            <a href="{{ route('resepsionis.pemilik.create') }}" 
               class="bg-white text-primary-700 px-5 py-2.5 rounded-lg hover:bg-primary-50 transition duration-200 font-medium shadow-sm flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Pemilik</span>
            </a>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Pemilik</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. WhatsApp</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pemiliks as $index => $pemilik)
                        <tr class="hover:bg-primary-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">{{ $pemilik->idpemilik }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                        <span class="text-primary-700 font-semibold text-sm">
                                            {{ substr($pemilik->nama ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $pemilik->nama ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2 text-sm text-gray-700">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    <span>{{ $pemilik->email ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700 max-w-xs truncate" title="{{ $pemilik->alamat ?? '-' }}">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    {{ $pemilik->alamat ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2 text-sm text-gray-700">
                                    <i class="fab fa-whatsapp text-green-500"></i>
                                    <span>{{ $pemilik->no_wa ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $punyaPet = DB::table('pet')->where('idpemilik', $pemilik->idpemilik)->exists();
                                @endphp

                                @if(!$punyaPet)
                                    <div class="flex gap-2">
                                        <a href="{{ route('resepsionis.pemilik.edit', $pemilik->idpemilik) }}" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition duration-200 shadow-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form action="{{ route('resepsionis.pemilik.destroy', $pemilik->idpemilik) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemilik ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-200 shadow-sm">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <button disabled
                                            class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed"
                                            title="Pemilik ini memiliki hewan, tidak bisa dihapus">
                                        <i class="fas fa-lock mr-1"></i>Terkunci
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-4xl text-gray-300"></i>
                                    </div>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">Belum ada data pemilik</p>
                                        <p class="text-sm text-gray-500 mt-1">Mulai tambahkan data pemilik pertama</p>
                                    </div>
                                    <a href="{{ route('resepsionis.pemilik.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition duration-200 shadow-sm">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Pemilik Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection