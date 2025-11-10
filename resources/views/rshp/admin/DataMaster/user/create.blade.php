@extends('layouts.lte.app')

@section('title', 'Tambah User')
@section('page', 'User')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
  <h1 class="text-2xl font-semibold mb-4 text-blue-600 flex items-center">
    <i class="fas fa-user-plus mr-2"></i> Tambah User
  </h1>

  @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <strong>Terjadi kesalahan:</strong>
      <ul class="list-disc list-inside mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
      <label class="block font-medium">Nama <span class="text-red-500">*</span></label>
      <input type="text" name="nama" value="{{ old('nama') }}"
             class="w-full border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300"
             placeholder="Masukkan nama lengkap" required>
    </div>

    <div>
      <label class="block font-medium">Email <span class="text-red-500">*</span></label>
      <input type="email" name="email" value="{{ old('email') }}"
             class="w-full border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300"
             placeholder="Masukkan email" required>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block font-medium">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password"
               class="w-full border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300"
               placeholder="Minimal 3 karakter" required>
      </div>
      <div>
        <label class="block font-medium">Konfirmasi Password <span class="text-red-500">*</span></label>
        <input type="password" name="password_confirmation"
               class="w-full border-gray-300 rounded-lg px-3 py-2 mt-1 focus:ring focus:ring-blue-300"
               placeholder="Ulangi password" required>
      </div>
    </div>

    <div>
      <label class="block font-medium">Role (Opsional)</label>
      <div class="grid md:grid-cols-3 gap-2 mt-2">
        @foreach ($roles as $role)
          <label class="flex items-center space-x-2">
            <input type="checkbox" name="role_ids[]" value="{{ $role->idrole }}" class="text-blue-500 focus:ring-blue-300">
            <span>{{ $role->nama_role }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <div class="flex justify-end space-x-2 pt-4">
      <a href="{{ route('admin.user.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
      </a>
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        <i class="fas fa-save mr-1"></i> Simpan
      </button>
    </div>
  </form>
</div>
@endsection
