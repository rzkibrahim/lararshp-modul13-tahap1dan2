@extends('layouts.lte.app')

@section('title', 'Data Pet')
@section('page', 'Pet')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Data Pet</h2>
            <div class="flex items-center space-x-4">
                <span class="text-blue-100">Total: {{ $pets->count() }} pet</span>
                <a href="{{ route('admin.pet.create') }}" 
                   class="bg-white text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-50 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Data
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-4 text-left font-semibold text-gray-700">No</th>
                        <th class="p-4 text-left font-semibold text-gray-700">ID Pet</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Nama Pet</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Jenis Kelamin</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Tanggal Lahir</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Jenis Hewan</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Ras Hewan</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Pemilik</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pets as $index => $pet)
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition duration-150">
                        <td class="p-4 text-gray-600">{{ $index + 1 }}</td>
                        <td class="p-4 text-gray-800 font-medium">{{ $pet->idpet }}</td>
                        <td class="p-4 text-gray-800">
                            <div class="font-medium">{{ $pet->nama ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $pet->warna_tanda ?? '-' }}</div>
                        </td>
                        <td class="p-4">
                            @if($pet->jenis_kelamin == 'M')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                ♂ Jantan
                            </span>
                            @elseif($pet->jenis_kelamin == 'F')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                ♀ Betina
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-800">
                            {{ $pet->tanggal_lahir ? \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d M Y') : '-' }}
                        </td>
                        <td class="p-4 text-gray-800">
                            {{ $pet->rasHewan->jenisHewan->nama_jenis_hewan ?? '-' }}
                        </td>
                        <td class="p-4 text-gray-800">
                            {{ $pet->rasHewan->nama_ras ?? '-' }}
                        </td>
                        <td class="p-4 text-gray-800">
                            {{ $pet->pemilik->user->nama ?? '-' }}
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.pet.edit', $pet->idpet) }}" 
                                   class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-200">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('admin.pet.destroy', $pet->idpet) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pet ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-200">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-gray-500">
                            <i class="fas fa-dog text-4xl mb-4 text-gray-300"></i>
                            <p class="text-lg">Tidak ada data pet</p>
                            <a href="{{ route('admin.pet.create') }}" 
                               class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                                Tambah data pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
