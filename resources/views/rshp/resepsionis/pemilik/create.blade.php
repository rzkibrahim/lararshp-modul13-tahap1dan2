@extends('layouts.lte.resepsionis')

@section('title', 'Tambah Data Pemilik')
@section('page_title', 'Tambah Data Pemilik')
@section('page_description', 'Registrasi pemilik hewan peliharaan baru')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Registrasi Pemilik Baru</h2>
                    <p class="text-primary-100 text-sm mt-1">Lengkapi formulir data pemilik</p>
                </div>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-4 m-6">
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-lightbulb text-amber-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-amber-800 font-medium">Pastikan pilih user yang belum terdaftar sebagai pemilik</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('resepsionis.pemilik.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                
                {{-- Pilih User --}}
                <div>
                    <label for="iduser" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-primary-500 mr-1"></i>
                        Pilih User <span class="text-red-500">*</span>
                    </label>
                    <select id="iduser" 
                            name="iduser" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('iduser') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->iduser }}" {{ old('iduser') == $user->iduser ? 'selected' : '' }}>
                                {{ $user->nama }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('iduser')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Hanya menampilkan user yang belum terdaftar sebagai pemilik
                    </p>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-primary-500 mr-1"></i>
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat" 
                              name="alamat" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('alamat') border-red-500 @enderror"
                              placeholder="Masukkan alamat lengkap (minimal 10 karakter)"
                              required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor WhatsApp --}}
                <div>
                    <label for="no_wa" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-whatsapp text-primary-500 mr-1"></i>
                        Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fab fa-whatsapp text-green-500"></i>
                        </div>
                        <input type="tel" 
                               id="no_wa" 
                               name="no_wa" 
                               value="{{ old('no_wa') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('no_wa') border-red-500 @enderror"
                               placeholder="Contoh: 08123456789 atau 6281234567890"
                               required>
                    </div>
                    @error('no_wa')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Format: 08xxxxxxxxxx atau +62xxxxxxxxxx
                    </p>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                <a href="{{ route('resepsionis.pemilik.index') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition duration-200 font-medium shadow-sm">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection