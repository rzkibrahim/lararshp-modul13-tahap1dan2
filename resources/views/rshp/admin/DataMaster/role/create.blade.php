@extends('layouts.lte.app')

@section('title', 'Tambah Role')
@section('page', 'Role')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">

    {{-- Judul Halaman --}}
    <h1 class="text-2xl font-semibold mb-4 text-blue-600 flex items-center">
        <i class="fas fa-user-tag mr-2"></i> Tambah Role
    </h1>

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

    {{-- Form Tambah Role --}}
    <form action="{{ route('admin.role.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Nama Role --}}
        <div>
            <label for="nama_role" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Role <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_role" name="nama_role" value="{{ old('nama_role') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                   placeholder="Masukkan nama role" required>
        </div>

        {{-- Deskripsi Role --}}
        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
            </label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                      placeholder="Masukkan deskripsi role">{{ old('deskripsi') }}</textarea>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.role.index') }}" 
               class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
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
