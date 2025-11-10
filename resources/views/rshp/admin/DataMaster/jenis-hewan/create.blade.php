@extends('layouts.lte.app')

@section('title', 'Tambah Jenis Hewan')
@section('page', 'Jenis Hewan')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-8">

  <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
      <i class="fas fa-paw mr-2 text-amber-500"></i> Tambah Jenis Hewan
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

  {{-- Notifikasi Sukses --}}
  @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
          <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
      </div>
  @endif

  {{-- Form Tambah Jenis Hewan --}}
  <form action="{{ route('admin.jenis-hewan.store') }}" method="POST" class="space-y-6">
      @csrf

      <div>
          <label for="nama_jenis_hewan" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Jenis Hewan <span class="text-red-500">*</span>
          </label>
          <input type="text"
                 id="nama_jenis_hewan"
                 name="nama_jenis_hewan"
                 value="{{ old('nama_jenis_hewan') }}"
                 placeholder="Masukkan nama jenis hewan"
                 required
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('nama_jenis_hewan') border-red-500 @enderror">
          @error('nama_jenis_hewan')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
      </div>

      {{-- Tombol Aksi --}}
      <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
          <a href="{{ route('admin.jenis-hewan.index') }}" 
             class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg flex items-center transition duration-200">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
          </a>
          <button type="submit" 
                  class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg flex items-center shadow-md transition duration-200">
              <i class="fas fa-save mr-2"></i> Simpan
          </button>
      </div>
  </form>
</div>
@endsection
