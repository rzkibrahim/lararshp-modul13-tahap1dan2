@extends('layouts.lte.app')

@section('title', 'Tambah Pemilik')
@section('page', 'Pemilik')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">

  <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
      <i class="fas fa-users mr-2 text-blue-500"></i> Tambah Pemilik
  </h2>

  {{-- Notifikasi Error --}}
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

  {{-- Form Tambah Pemilik --}}
  <form action="{{ route('admin.pemilik.store') }}" method="POST" class="space-y-6">
      @csrf

      {{-- Pilih User --}}
      <div>
          <label for="iduser" class="block text-sm font-medium text-gray-700 mb-2">
              User <span class="text-red-500">*</span>
          </label>
          <select id="iduser" name="iduser" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                  required>
              <option value="">Pilih User</option>
              @foreach($users as $user)
                  <option value="{{ $user->iduser }}" {{ old('iduser') == $user->iduser ? 'selected' : '' }}>
                      {{ $user->nama }} ({{ $user->email }})
                  </option>
              @endforeach
          </select>
      </div>

      {{-- Alamat --}}
      <div>
          <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
              Alamat <span class="text-red-500">*</span>
          </label>
          <textarea id="alamat" name="alamat" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
      </div>

      {{-- Nomor WhatsApp --}}
      <div>
          <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-2">
              Nomor WhatsApp <span class="text-red-500">*</span>
          </label>
          <input type="tel" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                 placeholder="Contoh: 6281234567890" required>
          <p class="text-sm text-gray-500 mt-1">Format: 62xxxxxxxxxxx (tanpa + dan spasi)</p>
      </div>

      {{-- Tombol Aksi --}}
      <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
          <a href="{{ route('admin.pemilik.index') }}" 
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
