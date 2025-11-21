@extends('layouts.lte.dokter')

@section('title', 'Tambah Tindakan/Terapi - Dokter')
@section('page_title', 'Tambah Tindakan/Terapi')
@section('page_description', 'Tambah tindakan atau terapi baru ke rekam medis')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-xl font-semibold text-gray-900">Tambah Tindakan/Terapi</h5>
                    <p class="text-gray-500 text-sm mt-1">Rekam Medis #{{ $idRekamMedis }}</p>
                </div>
                <a href="{{ route('dokter.rekam-medis.show', $idRekamMedis) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <form action="{{ route('dokter.detail-rekam-medis.store', $idRekamMedis) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Pilih Tindakan/Terapi -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-200 bg-white">
                            <h6 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-procedures text-primary-600"></i>
                                <span>Pilih Tindakan/Terapi</span>
                            </h6>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="idkode_tindakan_terapi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tindakan/Terapi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="idkode_tindakan_terapi" id="idkode_tindakan_terapi" 
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                                        <option value="">Pilih Tindakan/Terapi</option>
                                        @foreach($kodeTindakanTerapi as $kode)
                                        <option value="{{ $kode->idkode_tindakan_terapi }}" 
                                                data-kategori="{{ $kode->nama_kategori }}"
                                                data-jenis="{{ $kode->nama_kategori_klinis }}"
                                                {{ old('idkode_tindakan_terapi') == $kode->idkode_tindakan_terapi ? 'selected' : '' }}>
                                            {{ $kode->kode }} - {{ $kode->deskripsi_tindakan_terapi }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('idkode_tindakan_terapi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Info Tindakan Terpilih -->
                                <div id="info-tindakan" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-blue-800">Kategori:</span>
                                            <span id="info-kategori" class="text-blue-700 ml-2">-</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-blue-800">Jenis Tindakan:</span>
                                            <span id="info-jenis" class="text-blue-700 ml-2">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Tambahan -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 border-b border-gray-200 bg-white">
                            <h6 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                <i class="fas fa-file-medical text-primary-600"></i>
                                <span>Detail Tambahan</span>
                            </h6>
                        </div>
                        <div class="p-6">
                            <div>
                                <label for="detail" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan Tambahan
                                </label>
                                <textarea name="detail" id="detail" rows="4" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                          placeholder="Masukkan keterangan tambahan tentang tindakan/terapi ini...">{{ old('detail') }}</textarea>
                                @error('detail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-xs mt-2">Opsional: Tambahkan catatan khusus tentang pelaksanaan tindakan/terapi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('dokter.rekam-medis.show', $idRekamMedis) }}" 
                       class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 px-6 py-3 rounded-lg font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Tindakan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectTindakan = document.getElementById('idkode_tindakan_terapi');
        const infoTindakan = document.getElementById('info-tindakan');
        const infoKategori = document.getElementById('info-kategori');
        const infoJenis = document.getElementById('info-jenis');

        selectTindakan.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                infoKategori.textContent = selectedOption.getAttribute('data-kategori');
                infoJenis.textContent = selectedOption.getAttribute('data-jenis');
                infoTindakan.classList.remove('hidden');
            } else {
                infoTindakan.classList.add('hidden');
            }
        });

        // Trigger change event on page load if there's a selected value
        if (selectTindakan.value) {
            selectTindakan.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection