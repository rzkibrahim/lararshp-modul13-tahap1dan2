@extends('layouts.lte.app')

@section('title', 'Tambah Ras Hewan')
@section('page', 'Ras Hewan')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-8">

  <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
      <i class="fas fa-dna mr-2 text-blue-500"></i> Tambah Ras Hewan
  </h2>

  {{-- Validasi Error --}}
  @if($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <ul class="list-disc list-inside">
              @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  {{-- Form Tambah Ras --}}
  <form action="{{ route('admin.ras-hewan.store') }}" method="POST" class="space-y-6">
      @csrf

      {{-- Nama Ras --}}
      <div>
          <label for="nama_ras" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Ras <span class="text-red-500">*</span>
          </label>
          <input type="text" id="nama_ras" name="nama_ras" value="{{ old('nama_ras') }}" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                 placeholder="Masukkan nama ras hewan" required>
      </div>

      {{-- Jenis Hewan --}}
      <div>
          <label for="idjenis_hewan" class="block text-sm font-medium text-gray-700 mb-2">
              Jenis Hewan <span class="text-red-500">*</span>
          </label>
          <select id="idjenis_hewan" name="idjenis_hewan" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
              <option value="">Pilih Jenis Hewan</option>
              @foreach($jenisHewan as $jenis)
                  <option value="{{ $jenis->idjenis_hewan }}" {{ old('idjenis_hewan') == $jenis->idjenis_hewan ? 'selected' : '' }}>
                      {{ $jenis->nama_jenis_hewan }}
                  </option>
              @endforeach
          </select>
      </div>

      {{-- Deskripsi --}}
      <div>
          <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
              Deskripsi
          </label>
          <textarea id="deskripsi" name="deskripsi" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Masukkan deskripsi ras hewan">{{ old('deskripsi') }}</textarea>
      </div>

      {{-- Tombol Aksi --}}
      <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
          <a href="{{ route('admin.ras-hewan.index') }}" 
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
