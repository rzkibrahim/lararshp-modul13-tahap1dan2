@extends('layouts.lte.resepsionis')

@section('title', 'Temu Dokter - RSHP')

@section('page_title', 'Temu Dokter & Antrian')
@section('page_description', 'Kelola pendaftaran dan antrian temu dokter')

@push('styles')
<style>
    /* Custom styles untuk elemen yang membutuhkan penyesuaian khusus */
    .queue-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #0067b6 0%, #004080 100%);
        color: white;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 103, 182, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Notification Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show flex items-center mb-4 p-4 rounded-lg border border-green-200 bg-green-50" role="alert">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            <div class="flex-1">
                <strong class="font-bold text-green-800">Berhasil!</strong>
                <span class="text-green-700 ml-1">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close text-green-600 hover:text-green-800" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show flex items-center mb-4 p-4 rounded-lg border border-red-200 bg-red-50" role="alert">
            <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
            <div class="flex-1">
                <strong class="font-bold text-red-800">Error!</strong>
                <span class="text-red-700 ml-1">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close text-red-600 hover:text-red-800" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Registration Header Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl p-6 mb-8 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h3 class="text-2xl font-bold mb-2">
                    <i class="fas fa-calendar-plus mr-2"></i> Tambah Antrian Temu Dokter
                </h3>
                <p class="text-blue-100 opacity-90">Daftarkan pet untuk konsultasi dengan dokter</p>
            </div>
            <div class="bg-white text-gray-700 px-4 py-2 rounded-lg border-2 border-gray-200 font-semibold">
                <i class="fas fa-calendar mr-2 text-blue-600"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Left Column: Forms --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Filter Form --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h5 class="text-lg font-semibold mb-4 text-gray-800">
                    <i class="fas fa-filter mr-2 text-blue-600"></i> Lihat Antrian Tanggal
                </h5>
                
                <form method="GET" action="{{ route('resepsionis.temu-dokter.index') }}" id="filterForm">
                    <input type="hidden" name="tanggal_daftar" value="{{ $selectedDaftarDate }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Tanggal
                            </label>
                            <input type="date" id="date" name="date" 
                                   class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   value="{{ $selectedDate }}" required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Registration Form --}}
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h5 class="text-lg font-semibold mb-4 text-gray-800">
                    <i class="fas fa-plus-circle mr-2 text-green-600"></i> Pendaftaran Baru
                </h5>
                
                <form method="POST" action="{{ route('resepsionis.temu-dokter.store') }}" id="daftarForm">
                    @csrf
                    
                    {{-- Tanggal Pendaftaran --}}
                    <div class="mb-4">
                        <label for="tanggalDaftar" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day mr-1"></i> Tanggal Pendaftaran
                        </label>
                        <input type="date" name="tanggal_daftar" id="tanggalDaftar" 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tanggal_daftar') border-red-500 @enderror"
                               value="{{ old('tanggal_daftar', $selectedDaftarDate) }}"
                               min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_daftar')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded mt-2">
                            <small class="text-blue-700 font-medium">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pilih tanggal untuk melihat pet yang tersedia
                            </small>
                        </div>
                    </div>

                    {{-- Pilih Pet --}}
                    <div class="mb-4">
                        <label for="petSelect" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-paw mr-1"></i> Pilih Pet
                        </label>
                        <select name="idpet" id="petSelect" 
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('idpet') border-red-500 @enderror" required>
                            <option value="">— Pilih Pet —</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->idpet }}" {{ old('idpet') == $pet->idpet ? 'selected' : '' }}>
                                    {{ $pet->nama }} — Pemilik: {{ $pet->pemilik->user->nama ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('idpet')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        
                        <small class="text-gray-500 block mt-2">
                            <i class="fas fa-check-circle mr-1 text-green-500"></i>
                            Pet tersedia pada: <strong class="text-gray-700">{{ \Carbon\Carbon::parse($selectedDaftarDate)->format('d/m/Y') }}</strong>
                        </small>
                    </div>

                    {{-- Pilih Dokter --}}
                    <div class="mb-6">
                        <label for="doctorSelect" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-md mr-1"></i> Pilih Dokter
                        </label>
                        <select name="idrole_user" id="doctorSelect" 
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('idrole_user') border-red-500 @enderror" required>
                            <option value="">— Pilih Dokter —</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->idrole_user }}" {{ old('idrole_user') == $doctor->idrole_user ? 'selected' : '' }}>
                                    Dr. {{ $doctor->user->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('idrole_user')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Daftar
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column: Queue Table --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h5 class="text-xl font-bold mb-2 sm:mb-0">
                            <i class="fas fa-list-ol mr-2"></i>
                            Antrian Tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                        </h5>
                        <span class="bg-white text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $antrian->count() }} Antrian
                        </span>
                    </div>
                </div>

                @if($antrian->count() === 0)
                    <div class="text-center py-12 px-6">
                        <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
                        <h5 class="text-gray-500 text-lg font-semibold mb-2">Tidak Ada Antrian</h5>
                        <p class="text-gray-400">Belum ada antrian pada tanggal ini</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">No. Urut</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Daftar</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pet</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dokter</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-48">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($antrian as $row)
                                    @php
                                        $status_val = (int)$row->status;
                                        $status_config = [
                                            0 => ['class' => 'bg-yellow-50 text-yellow-800 border-yellow-200', 'text' => 'Menunggu', 'icon' => 'clock'],
                                            1 => ['class' => 'bg-green-50 text-green-800 border-green-200', 'text' => 'Selesai', 'icon' => 'check'],
                                            2 => ['class' => 'bg-red-50 text-red-800 border-red-200', 'text' => 'Batal', 'icon' => 'times']
                                        ];
                                        $status = $status_config[$status_val];
                                        $waktu = \Carbon\Carbon::parse($row->waktu_daftar);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-4">
                                            <div class="queue-number">
                                                {{ $row->no_urut }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-500">Terdaftar</div>
                                            <div class="font-semibold text-gray-900">{{ $waktu->format('H:i') }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-paw mr-2 text-gray-400"></i>
                                                <span class="font-semibold text-gray-900">{{ $row->nama_pet }}</span>
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $row->nama_pemilik ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-user-md mr-2 text-gray-400"></i>
                                                <span class="font-semibold text-gray-900">{{ $row->nama_dokter }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $status['class'] }}">
                                                <i class="fas fa-{{ $status['icon'] }} mr-1"></i>
                                                {{ $status['text'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($status_val === 0)
                                                <div class="flex space-x-2">
                                                    <form method="POST" action="{{ route('resepsionis.temu-dokter.update-status') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="idreservasi_dokter" value="{{ $row->idreservasi_dokter }}">
                                                        <input type="hidden" name="status" value="1">
                                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-semibold text-sm transition-all duration-200 transform hover:-translate-y-0.5 shadow hover:shadow-md">
                                                            <i class="fas fa-check mr-1"></i> Selesai
                                                        </button>
                                                    </form>
                                                    
                                                    <form method="POST" action="{{ route('resepsionis.temu-dokter.update-status') }}" 
                                                          class="inline"
                                                          onsubmit="return confirm('Batalkan antrian ini?')">
                                                        @csrf
                                                        <input type="hidden" name="idreservasi_dokter" value="{{ $row->idreservasi_dokter }}">
                                                        <input type="hidden" name="status" value="2">
                                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg font-semibold text-sm transition-all duration-200 transform hover:-translate-y-0.5 shadow hover:shadow-md">
                                                            <i class="fas fa-times mr-1"></i> Batal
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic text-sm">
                                                    <i class="fas fa-ban mr-1"></i>Tidak ada aksi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-reload pets when date changes
        $('#tanggalDaftar').on('change', function() {
            const date = $(this).val();
            $('#filterForm input[name="tanggal_daftar"]').val(date);
            $('#filterForm').submit();
        });

        // Form validation enhancement
        $('#daftarForm').on('submit', function(e) {
            const pet = $('#petSelect').val();
            const doctor = $('#doctorSelect').val();
            
            if (!pet || !doctor) {
                e.preventDefault();
                alert('Harap lengkapi semua field!');
                return false;
            }
        });
    });
</script>
@endpush