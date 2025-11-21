@extends('layouts.lte.resepsionis')

@section('title', 'Edit Data Pemilik')
@section('page_title', 'Edit Data Pemilik')
@section('page_description', 'Perbarui informasi data pemilik')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Data Pemilik</h2>
                    <p class="text-primary-100 text-sm mt-1">{{ $pemilik->nama }}</p>
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
                    <p class="text-sm text-blue-800">Pastikan data pemilik akurat untuk kemudahan komunikasi dan pelayanan.</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('resepsionis.pemilik.update', $pemilik->idpemilik) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Pemilik --}}
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-primary-500 mr-1"></i>
                        Nama Pemilik <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama"
                           value="{{ old('nama', $pemilik->nama) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-primary-500 mr-1"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email"
                           value="{{ old('email', $pemilik->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('email') border-red-500 @enderror"
                           placeholder="email@example.com"
                           required>
                    @error('email')
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
                               value="{{ old('no_wa', $pemilik->no_wa) }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('no_wa') border-red-500 @enderror"
                               placeholder="08xxxxxxxxxx"
                               required>
                    </div>
                    @error('no_wa')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-primary-500 mr-1"></i>
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat" 
                              name="alamat" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 @error('alamat') border-red-500 @enderror"
                              placeholder="Masukkan alamat lengkap"
                              required>{{ old('alamat', $pemilik->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
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
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection