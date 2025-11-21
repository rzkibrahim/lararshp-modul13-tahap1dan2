@extends('layouts.lte.resepsionis')

@section('title', 'Data Pet')
@section('page_title', 'Data Pet')
@section('page_description', 'Kelola data hewan peliharaan yang terdaftar')

@section('content')
<div class="space-y-6">

    {{-- Card Data Pet --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-paw text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Daftar Pet</h2>
                    <p class="text-primary-100 text-sm">Total: {{ $pets->count() }} pet terdaftar</p>
                </div>
            </div>
            <a href="{{ route('resepsionis.pet.create') }}" 
               class="bg-white text-primary-700 px-5 py-2.5 rounded-lg hover:bg-primary-50 transition duration-200 font-medium shadow-sm flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Pet</span>
            </a>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Pet</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Pet</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Lahir</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Hewan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ras Hewan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pets as $index => $pet)
                        <tr class="hover:bg-primary-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">{{ $pet->idpet }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $pet->nama_pet ?? '-' }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-palette text-gray-400 mr-1"></i>{{ $pet->warna_tanda ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pet->jenis_kelamin == 'M')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-mars mr-1"></i> Jantan
                                    </span>
                                @elseif($pet->jenis_kelamin == 'F')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                        <i class="fas fa-venus mr-1"></i> Betina
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if($pet->tanggal_lahir)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                        <span>{{ \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d M Y') }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-800">
                                    {{ $pet->nama_jenis_hewan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $pet->nama_ras ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $pet->nama_pemilik ?? '-' }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>{{ $pet->email_pemilik ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('resepsionis.pet.edit', $pet->idpet) }}" 
                                       class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition duration-200 shadow-sm">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('resepsionis.pet.destroy', $pet->idpet) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pet ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-200 shadow-sm">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-dog text-4xl text-gray-300"></i>
                                    </div>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">Belum ada data pet</p>
                                        <p class="text-sm text-gray-500 mt-1">Mulai tambahkan data pet pertama Anda</p>
                                    </div>
                                    <a href="{{ route('resepsionis.pet.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition duration-200 shadow-sm">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Pet Pertama
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