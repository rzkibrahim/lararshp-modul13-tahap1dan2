@extends('layouts.lte.resepsionis')

@section('title', 'Edit Data Pet')
@section('page_title', 'Edit Data Pet')
@section('page_description', 'Perbarui informasi data hewan peliharaan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-paw text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Data Pet</h2>
                    <p class="text-primary-100 text-sm mt-1">{{ $pet->nama }}</p>
                </div>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-4 m-6">
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-blue-800">Pastikan data pet akurat, terutama jenis hewan dan informasi pemiliknya.</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('resepsionis.pet.update', $pet->idpet) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Pet --}}
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-primary-500 mr-1"></i>
                        Nama Pet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama"
                           value="{{ old('nama', $pet->nama) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                           placeholder="Masukkan nama pet"
                           required>
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-venus-mars text-primary-500 mr-1"></i>
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_kelamin" 
                            name="jenis_kelamin"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                            required>
                        <option value="M" {{ $pet->jenis_kelamin == 'M' ? 'selected' : '' }}>♂ Jantan</option>
                        <option value="F" {{ $pet->jenis_kelamin == 'F' ? 'selected' : '' }}>♀ Betina</option>
                    </select>
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-primary-500 mr-1"></i>
                        Tanggal Lahir
                    </label>
                    <input type="date" 
                           id="tanggal_lahir" 
                           name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $pet->tanggal_lahir) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200">
                </div>

                {{-- Warna/Tanda Khusus --}}
                <div>
                    <label for="warna_tanda" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-palette text-primary-500 mr-1"></i>
                        Warna / Tanda Khusus
                    </label>
                    <input type="text" 
                           id="warna_tanda" 
                           name="warna_tanda"
                           value="{{ old('warna_tanda', $pet->warna_tanda) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                           placeholder="Contoh: Putih bintik hitam">
                </div>

                {{-- Ras Hewan --}}
                <div>
                    <label for="idras_hewan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-dna text-primary-500 mr-1"></i>
                        Ras Hewan <span class="text-red-500">*</span>
                    </label>
                    <select id="idras_hewan" 
                            name="idras_hewan" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                            required>
                        <option value="">-- Pilih Ras Hewan --</option>
                        @foreach($rasHewan as $ras)
                        <option value="{{ $ras->idras_hewan }}" {{ $pet->idras_hewan == $ras->idras_hewan ? 'selected' : '' }}>
                            {{ $ras->nama_ras }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pemilik --}}
                <div>
                    <label for="idpemilik" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-primary-500 mr-1"></i>
                        Pemilik <span class="text-red-500">*</span>
                    </label>
                    <select id="idpemilik" 
                            name="idpemilik" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200"
                            required>
                        <option value="">-- Pilih Pemilik --</option>
                        @foreach($pemilik as $p)
                        <option value="{{ $p->idpemilik }}" {{ $pet->idpemilik == $p->idpemilik ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('resepsionis.pet.index') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition duration-200 font-medium shadow-sm">
                    <i class="fas fa-save mr-2"></i>
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection