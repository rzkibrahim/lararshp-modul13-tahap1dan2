@extends('layouts.lte.app')

@section('title', 'Tambah Pet')
@section('page', 'Pet')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-dog mr-2 text-blue-500"></i> Tambah Pet
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

    {{-- Form Tambah Pet --}}
    <form action="{{ route('admin.pet.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama Pet --}}
            <div class="md:col-span-2">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Pet <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Masukkan nama pet" required>
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="M" {{ old('jenis_kelamin') == 'M' ? 'selected' : '' }}>Jantan</option>
                    <option value="F" {{ old('jenis_kelamin') == 'F' ? 'selected' : '' }}>Betina</option>
                </select>
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Lahir
                </label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            {{-- Warna/Tanda --}}
            <div class="md:col-span-2">
                <label for="warna_tanda" class="block text-sm font-medium text-gray-700 mb-2">
                    Warna/Tanda Khusus
                </label>
                <input type="text" id="warna_tanda" name="warna_tanda" value="{{ old('warna_tanda') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                    placeholder="Contoh: Putih dengan bintik hitam">
            </div>

            {{-- Ras Hewan --}}
            <div>
                <label for="idras_hewan" class="block text-sm font-medium text-gray-700 mb-2">
                    Ras Hewan <span class="text-red-500">*</span>
                </label>
                <select id="idras_hewan" name="idras_hewan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                    <option value="">Pilih Ras Hewan</option>
                    @foreach($rasHewan as $ras)
                    <option value="{{ $ras->idras_hewan }}" {{ old('idras_hewan') == $ras->idras_hewan ? 'selected' : '' }}>
                        {{ $ras->nama_ras }} ({{ $ras->nama_jenis_hewan }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Pemilik --}}
            <div>
                <label for="idpemilik" class="block text-sm font-medium text-gray-700 mb-2">
                    Pemilik <span class="text-red-500">*</span>
                </label>
                <select id="idpemilik" name="idpemilik"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                    <option value="">Pilih Pemilik</option>
                    @foreach($pemiliks as $pemilik)
                    <option value="{{ $pemilik->idpemilik }}" {{ old('idpemilik') == $pemilik->idpemilik ? 'selected' : '' }}>
                        {{ $pemilik->nama }} - {{ $pemilik->email }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.pet.index') }}"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button type="submit"
                class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition duration-200">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection