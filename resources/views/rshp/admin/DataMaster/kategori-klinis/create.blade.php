@extends('layouts.lte.app')

@section('title', 'Tambah Kategori Klinis')
@section('page', 'Kategori Klinis')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-8">

  <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
      <i class="fas fa-stethoscope mr-2 text-blue-500"></i> Tambah Kategori Klinis
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
  <form action="{{ route('admin.kategori-klinis.store') }}" method="POST" class="space-y-6">
      @csrf

      <div>
          <label for="nama_kategori_klinis" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Kategori Klinis <span class="text-red-500">*</span>
          </label>
          <input type="text" 
                 name="nama_kategori_klinis" 
                 id="nama_kategori_klinis"
                 value="{{ old('nama_kategori_klinis') }}"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                 placeholder="Masukkan nama kategori klinis"
                 required>
          @error('nama_kategori_klinis')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
      </div>

      <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
          <a href="{{ route('admin.kategori-klinis.index') }}" 
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
