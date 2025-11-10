@extends('layouts.lte.app')

@section('title', 'Tambah Kode Tindakan & Terapi')
@section('page', 'Kode Tindakan & Terapi')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">

  <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
      <i class="fas fa-procedures mr-2 text-blue-500"></i> Tambah Kode Tindakan & Terapi
  </h2>

  {{-- Notifikasi Error --}}
  @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <ul class="list-disc list-inside">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  {{-- Form Tambah --}}
  <form action="{{ route('admin.kode-tindakan-terapi.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          {{-- Kode --}}
          <div>
              <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                  Kode <span class="text-red-500">*</span>
              </label>
              <input type="text" 
                     name="kode" 
                     id="kode"
                     value="{{ old('kode') }}"
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                     placeholder="Masukkan kode (max 5 karakter)"
                     maxlength="5"
                     required>
              @error('kode')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
          </div>

          {{-- Kategori --}}
          <div>
              <label for="idkategori" class="block text-sm font-medium text-gray-700 mb-2">
                  Kategori <span class="text-red-500">*</span>
              </label>
              <select name="idkategori" id="idkategori"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                      required>
                  <option value="">Pilih Kategori</option>
                  @foreach($kategori as $kat)
                      <option value="{{ $kat->idkategori }}" {{ old('idkategori') == $kat->idkategori ? 'selected' : '' }}>
                          {{ $kat->nama_kategori }}
                      </option>
                  @endforeach
              </select>
              @error('idkategori')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
          </div>

          {{-- Kategori Klinis --}}
          <div>
              <label for="idkategori_klinis" class="block text-sm font-medium text-gray-700 mb-2">
                  Kategori Klinis <span class="text-red-500">*</span>
              </label>
              <select name="idkategori_klinis" id="idkategori_klinis"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                      required>
                  <option value="">Pilih Kategori Klinis</option>
                  @foreach($kategoriKlinis as $kk)
                      <option value="{{ $kk->idkategori_klinis }}" {{ old('idkategori_klinis') == $kk->idkategori_klinis ? 'selected' : '' }}>
                          {{ $kk->nama_kategori_klinis }}
                      </option>
                  @endforeach
              </select>
              @error('idkategori_klinis')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
          </div>
      </div>

      {{-- Deskripsi --}}
      <div>
          <label for="deskripsi_tindakan_terapi" class="block text-sm font-medium text-gray-700 mb-2">
              Deskripsi Tindakan/Terapi <span class="text-red-500">*</span>
          </label>
          <textarea name="deskripsi_tindakan_terapi" 
                    id="deskripsi_tindakan_terapi"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Masukkan deskripsi tindakan atau terapi"
                    required>{{ old('deskripsi_tindakan_terapi') }}</textarea>
          @error('deskripsi_tindakan_terapi')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
      </div>

      {{-- Tombol Aksi --}}
      <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
          <a href="{{ route('admin.kode-tindakan-terapi.index') }}" 
             class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
              <i class="fas fa-arrow-left mr-2"></i>Kembali
          </a>
          <button type="submit" 
                  class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
              <i class="fas fa-save mr-2"></i>Simpan
          </button>
      </div>
  </form>
</div>
@endsection
