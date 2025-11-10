@extends('layouts.lte.app')

@section('title', 'Dashboard Admin')
@section('page', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">

  {{-- Pesan sukses --}}
  @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  {{-- Judul --}}
  <h2 class="text-3xl font-bold mb-6 text-gray-800">
    Selamat Datang, {{ session('user_name') ?? 'Admin' }}!
  </h2>

  {{-- Dashboard Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-gray-500 text-sm font-semibold">Total User</h3>
      <p class="text-3xl font-bold text-blue-600 mt-2">150</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-gray-500 text-sm font-semibold">Total Pemilik</h3>
      <p class="text-3xl font-bold text-green-600 mt-2">320</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-gray-500 text-sm font-semibold">Total Pet</h3>
      <p class="text-3xl font-bold text-purple-600 mt-2">450</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-gray-500 text-sm font-semibold">Total Transaksi</h3>
      <p class="text-3xl font-bold text-orange-600 mt-2">89</p>
    </div>
  </div>

  {{-- Informasi Akun --}}
  <div class="mt-8 bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
    <h3 class="text-xl font-bold mb-4">Informasi Akun</h3>
    <div class="grid sm:grid-cols-2 gap-4">
      <div>
        <p class="text-gray-600">Nama:</p>
        <p class="font-semibold">{{ session('user_name') ?? '-' }}</p>
      </div>
      <div>
        <p class="text-gray-600">Email:</p>
        <p class="font-semibold">{{ session('user_email') ?? '-' }}</p>
      </div>
      <div>
        <p class="text-gray-600">Role:</p>
        <p class="font-semibold">{{ session('user_role_name') ?? 'Administrator' }}</p>
      </div>
      <div>
        <p class="text-gray-600">Status:</p>
        <p class="font-semibold text-green-600">{{ session('user_status') ?? 'Aktif' }}</p>
      </div>
    </div>
  </div>

  {{-- Tombol Logout (hanya di Dashboard) --}}
  <div class="mt-6 flex justify-center">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow">
        <i class="fas fa-sign-out-alt mr-2"></i> Logout
      </button>
    </form>
  </div>

</div>
@endsection
