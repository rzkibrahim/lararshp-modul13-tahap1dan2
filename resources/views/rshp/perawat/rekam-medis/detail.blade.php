@extends('layouts.lte.perawat')

@section('title', 'Detail Rekam Medis - Perawat')
@section('page_title', 'Detail Rekam Medis #' . $header->idrekam_medis)
@section('page_description', 'Kelola data pemeriksaan dan tindakan')

@section('content')
<div class="space-y-6">
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Info Header --}}
    <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
        <p class="text-sm text-gray-700">
            <b>Pet:</b> {{ $header->nama_pet }} — 
            <b>Pemilik:</b> {{ $header->nama_pemilik ?? '-' }} — 
            <b>Dokter:</b> {{ $header->nama_dokter ?? '-' }}
        </p>
    </div>

    {{-- Data Pemeriksaan --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Data Pemeriksaan</h2>
        
        <form method="POST" action="{{ route('perawat.rekam-medis.update-header', $header->idrekam_medis) }}" class="space-y-6">
            @csrf
            @method('POST')

            <div>
                <label for="anamnesa" class="block text-sm font-medium text-gray-700 mb-2">
                    Anamnesa
                </label>
                <textarea id="anamnesa" name="anamnesa" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('anamnesa', $header->anamnesa) }}</textarea>
            </div>

            <div>
                <label for="temuan_klinis" class="block text-sm font-medium text-gray-700 mb-2">
                    Temuan Klinis
                </label>
                <textarea id="temuan_klinis" name="temuan_klinis" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('temuan_klinis', $header->temuan_klinis) }}</textarea>
            </div>

            <div>
                <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                    Diagnosa
                </label>
                <textarea id="diagnosa" name="diagnosa" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('diagnosa', $header->diagnosa) }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('perawat.rekam-medis.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors">
                    Kembali
                </a>
                <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Tindakan Terapi --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Tindakan Terapi</h2>
        
        {{-- Form Tambah Tindakan --}}
        <form method="POST" action="{{ route('perawat.rekam-medis.create-detail', $header->idrekam_medis) }}" 
              class="bg-gray-50 p-4 rounded-lg mb-6">
            @csrf
            
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="idkode_tindakan_terapi" class="block text-sm font-medium text-gray-700 mb-2">
                        Tindakan *
                    </label>
                    <select id="idkode_tindakan_terapi" name="idkode_tindakan_terapi" required
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">— pilih tindakan —</option>
                        @foreach($listKode as $kode)
                            <option value="{{ $kode->idkode_tindakan_terapi }}">{{ $kode->label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1">
                    <label for="detail" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (opsional)
                    </label>
                    <input type="text" id="detail" name="detail" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Masukkan catatan...">
                </div>
                
                <div>
                    <button type="submit" 
                            class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded transition-colors h-[42px]">
                        Tambah
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabel Tindakan --}}
        @if($detailTindakan->count() === 0)
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-procedures text-4xl mb-3"></i>
                <p>Belum ada tindakan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Kode</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Deskripsi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Kategori</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Klinis</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Catatan</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($detailTindakan as $row)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $row->kode }}</td>
                            <td class="px-4 py-3 text-sm">{{ $row->deskripsi_tindakan_terapi }}</td>
                            <td class="px-4 py-3 text-sm">{{ $row->nama_kategori }}</td>
                            <td class="px-4 py-3 text-sm">{{ $row->nama_kategori_klinis }}</td>
                            <td class="px-4 py-3 text-sm">{{ $row->detail }}</td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    {{-- Form Edit --}}
                                    <form method="POST" 
                                          action="{{ route('perawat.rekam-medis.update-detail', ['id' => $header->idrekam_medis, 'idDetail' => $row->iddetail_rekam_medis]) }}"
                                          class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        
                                        <select name="idkode_tindakan_terapi" required
                                            class="text-xs px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500">
                                            @foreach($listKode as $kode)
                                                <option value="{{ $kode->idkode_tindakan_terapi }}" 
                                                    {{ $kode->idkode_tindakan_terapi == $row->idkode_tindakan_terapi ? 'selected' : '' }}>
                                                    {{ Str::limit($kode->label, 20) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        <input type="text" name="detail" value="{{ $row->detail }}" 
                                               class="text-xs px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500 w-24"
                                               placeholder="catatan">
                                               
                                        <button type="submit" 
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                            Simpan
                                        </button>
                                    </form>
                                    
                                    {{-- Form Hapus --}}
                                    <form method="POST" 
                                          action="{{ route('perawat.rekam-medis.delete-detail', ['id' => $header->idrekam_medis, 'idDetail' => $row->iddetail_rekam_medis]) }}"
                                          onsubmit="return confirm('Hapus tindakan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    // Confirm delete action
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus tindakan ini?');
    }
</script>
@endsection