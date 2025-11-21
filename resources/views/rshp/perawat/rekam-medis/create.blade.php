@extends('layouts.lte.perawat')

@section('title', 'Buat Rekam Medis - Perawat')
@section('page_title', 'Buat Rekam Medis Baru')
@section('page_description', 'Buat rekam medis untuk pasien')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Buat Rekam Medis Baru</h2>
        
        <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-700">
                <b>Reservasi:</b> #{{ $info->idreservasi_dokter }} (No. {{ $info->no_urut }}) •
                <b>Waktu Daftar:</b> {{ $info->waktu_daftar }}<br>
                <b>Pet:</b> {{ $info->nama_pet }} — <b>Pemilik:</b> {{ $info->nama_pemilik }}
            </p>
        </div>

        <form method="POST" action="{{ route('perawat.rekam-medis.store') }}" class="space-y-6">
            @csrf
            
            <input type="hidden" name="idreservasi" value="{{ $info->idreservasi_dokter }}">
            <input type="hidden" name="idpet" value="{{ $info->idpet }}">

            <div>
                <label for="dokter_pemeriksa" class="block text-sm font-medium text-gray-700 mb-2">
                    Dokter Pemeriksa *
                </label>
                <select id="dokter_pemeriksa" name="dokter_pemeriksa" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">— pilih dokter —</option>
                    @foreach($listDokter as $d)
                        <option value="{{ $d->idrole_user }}">{{ $d->nama }}</option>
                    @endforeach
                </select>
                @error('dokter_pemeriksa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="anamnesa" class="block text-sm font-medium text-gray-700 mb-2">
                    Anamnesa
                </label>
                <textarea id="anamnesa" name="anamnesa" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Masukkan anamnesa...">{{ old('anamnesa') }}</textarea>
                @error('anamnesa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="temuan_klinis" class="block text-sm font-medium text-gray-700 mb-2">
                    Temuan Klinis
                </label>
                <textarea id="temuan_klinis" name="temuan_klinis" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Masukkan temuan klinis...">{{ old('temuan_klinis') }}</textarea>
                @error('temuan_klinis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                    Diagnosa
                </label>
                <textarea id="diagnosa" name="diagnosa" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Masukkan diagnosa...">{{ old('diagnosa') }}</textarea>
                @error('diagnosa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6">
                <a href="{{ route('perawat.rekam-medis.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Simpan & Lanjut ke Detail
                </button>
            </div>
        </form>
    </div>
</div>
@endsection