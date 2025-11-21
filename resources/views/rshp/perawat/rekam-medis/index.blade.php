@extends('layouts.lte.perawat')

@section('title', 'Rekam Medis - Perawat')
@section('page_title', 'Rekam Medis')
@section('page_description', 'Kelola rekam medis pasien')

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

    {{-- Filter dan Kalender --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Filter dan Kalender</h2>
        
        {{-- Form Filter --}}
        <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Filter Per Hari --}}
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Filter Per Hari
                    </label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                {{-- Filter Per Bulan --}}
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">
                        Filter Per Bulan
                    </label>
                    <input type="month" id="bulan" name="bulan" value="{{ $bulan }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                {{-- Filter Per Tahun --}}
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                        Filter Per Tahun
                    </label>
                    <select id="tahun" name="tahun" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="flex space-x-2">
                <button type="submit" name="action" value="filter"
                        class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded transition-colors">
                    Terapkan Filter
                </button>
                <a href="{{ route('perawat.rekam-medis.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors">
                    Reset
                </a>
                
                {{-- Quick Navigation untuk Bulan --}}
                @if(request('bulan'))
                <div class="flex space-x-2 ml-auto">
                    <button type="submit" name="action" value="prev_month" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="submit" name="action" value="next_month"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                @endif
            </div>
        </form>

        {{-- Statistik --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Total Reservasi</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $statistik['total_reservasi'] }}</p>
                    </div>
                    <i class="fas fa-calendar-plus text-blue-500 text-2xl"></i>
                </div>
                <p class="text-xs text-blue-600 mt-2">
                    @if(request('tanggal'))
                        Pada tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                    @elseif(request('bulan'))
                        Pada bulan {{ \Carbon\Carbon::parse($bulan)->format('M Y') }}
                    @elseif(request('tahun'))
                        Pada tahun {{ $tahun }}
                    @else
                        Hari ini
                    @endif
                </p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Total Rekam Medis</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $statistik['total_rekam_medis'] }}</p>
                    </div>
                    <i class="fas fa-file-medical text-green-500 text-2xl"></i>
                </div>
                <p class="text-xs text-green-600 mt-2">
                    @if(request('tanggal'))
                        Pada tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                    @elseif(request('bulan'))
                        Pada bulan {{ \Carbon\Carbon::parse($bulan)->format('M Y') }}
                    @elseif(request('tahun'))
                        Pada tahun {{ $tahun }}
                    @else
                        Hari ini
                    @endif
                </p>
            </div>
        </div>

        {{-- Kalender Mini --}}
        @if(request('bulan'))
        <div class="mt-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">
                    Kalender Bulan {{ \Carbon\Carbon::parse($bulan)->format('F Y') }}
                </h3>
                <div class="flex space-x-2">
                    <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="inline">
                        <input type="hidden" name="bulan" value="{{ \Carbon\Carbon::parse($bulan)->subMonth()->format('Y-m') }}">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                            <i class="fas fa-chevron-left"></i> Bulan Sebelumnya
                        </button>
                    </form>
                    <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="inline">
                        <input type="hidden" name="bulan" value="{{ \Carbon\Carbon::parse($bulan)->addMonth()->format('Y-m') }}">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                            Bulan Berikutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-7 gap-1 text-center">
                    {{-- Header Hari --}}
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                        <div class="text-sm font-semibold text-gray-600 py-2">{{ substr($day, 0, 3) }}</div>
                    @endforeach
                    
                    {{-- Hari dalam Bulan --}}
                    @php
                        $firstDay = \Carbon\Carbon::parse($bulan . '-01');
                        $daysInMonth = $firstDay->daysInMonth;
                        $startDay = $firstDay->dayOfWeekIso - 1; // Monday = 0
                        
                        // Isi hari kosong di awal bulan
                        for ($i = 0; $i < $startDay; $i++) {
                            echo '<div class="p-2"></div>';
                        }
                        
                        // Isi hari dalam bulan
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $currentDate = sprintf('%04d-%02d-%02d', $firstDay->year, $firstDay->month, $day);
                            $isToday = $currentDate == date('Y-m-d');
                            $reservasiCount = $statistik['reservasi_per_hari'][$currentDate] ?? 0;
                            $rekamMedisCount = $statistik['rekam_medis_per_hari'][$currentDate] ?? 0;
                            
                            $bgColor = $isToday ? 'bg-primary-100 border-2 border-primary-300' : 'bg-white border border-gray-200';
                            $textColor = $isToday ? 'text-primary-800 font-bold' : 'text-gray-700';
                    @endphp
                    
                    <div class="{{ $bgColor }} rounded-lg p-2 min-h-20 {{ $textColor }} calendar-day">
                        <div class="flex justify-between items-start">
                            <span class="text-sm">{{ $day }}</span>
                            @if($reservasiCount > 0 || $rekamMedisCount > 0)
                            <div class="flex flex-col items-end space-y-1">
                                @if($reservasiCount > 0)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-1 rounded" title="{{ $reservasiCount }} reservasi">
                                        {{ $reservasiCount }}R
                                    </span>
                                @endif
                                @if($rekamMedisCount > 0)
                                    <span class="bg-green-100 text-green-800 text-xs px-1 rounded" title="{{ $rekamMedisCount }} rekam medis">
                                        {{ $rekamMedisCount }}RM
                                    </span>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        {{-- Quick Action untuk hari ini --}}
                        @if($isToday)
                        <div class="mt-2">
                            <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="text-center">
                                <input type="hidden" name="tanggal" value="{{ $currentDate }}">
                                <button type="submit" class="text-xs bg-primary-500 hover:bg-primary-600 text-white px-2 py-1 rounded w-full transition-colors">
                                    Lihat Hari Ini
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="mt-1">
                            <form method="GET" action="{{ route('perawat.rekam-medis.index') }}">
                                <input type="hidden" name="tanggal" value="{{ $currentDate }}">
                                <button type="submit" class="text-xs text-gray-500 hover:text-primary-600 transition-colors w-full text-left">
                                    lihat →
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    @php
                        }
                    @endphp
                </div>
            </div>
            
            {{-- Legend --}}
            <div class="mt-4 flex flex-wrap gap-4 text-xs">
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-blue-100 border border-blue-300 rounded"></div>
                    <span>Reservasi (R)</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-green-100 border border-green-300 rounded"></div>
                    <span>Rekam Medis (RM)</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-primary-100 border-2 border-primary-300 rounded"></div>
                    <span>Hari Ini</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Quick Links untuk Bulan Berjalan --}}
        <div class="mt-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Akses Cepat:</h4>
            <div class="flex flex-wrap gap-2">
                <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="inline">
                    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm transition-colors">
                        Hari Ini
                    </button>
                </form>
                <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="inline">
                    <input type="hidden" name="bulan" value="{{ date('Y-m') }}">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                        Bulan Ini
                    </button>
                </form>
                <form method="GET" action="{{ route('perawat.rekam-medis.index') }}" class="inline">
                    <input type="hidden" name="tahun" value="{{ date('Y') }}">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors">
                        Tahun Ini
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Reservasi Menunggu --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Reservasi Rekam Medis</h2>
            <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-semibold">
                @if(request('tanggal'))
                    {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
                @elseif(request('bulan'))
                    {{ \Carbon\Carbon::parse($bulan)->format('F Y') }}
                @elseif(request('tahun'))
                    Tahun {{ $tahun }}
                @else
                    Hari Ini
                @endif
            </span>
        </div>
        
        @if($reservasi->count() === 0)
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-user-times text-4xl mb-3"></i>
                <p>Tidak ada reservasi yang menunggu pembuatan rekam medis.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Reservasi</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Waktu Daftar</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pet</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pemilik</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reservasi as $r)
                        <tr>
                            <td class="px-4 py-3 text-sm">
                                #{{ $r->idreservasi_dokter }} (No.{{ $r->no_urut }})
                            </td>
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($r->waktu_daftar)->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $r->nama_pet }}</td>
                            <td class="px-4 py-3 text-sm">{{ $r->nama_pemilik }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('perawat.rekam-medis.create', ['idReservasi' => $r->idreservasi_dokter, 'idPet' => $r->idpet]) }}" 
                                   class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    Buat Rekam Medis
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Rekam Medis Terbaru --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Rekam Medis Terbaru</h2>
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ $listRM->count() }}
            </span>
        </div>
        
        @if($listRM->count() === 0)
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-file-medical text-4xl mb-3"></i>
                <p>Belum ada rekam medis.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID RM</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dibuat</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pet</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pemilik</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Anamnesa</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Diagnosa</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($listRM as $rm)
                        <tr>
                            <td class="px-4 py-3 text-sm font-semibold">#{{ $rm->idrekam_medis }}</td>
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($rm->created_at)->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $rm->nama_pet }}</td>
                            <td class="px-4 py-3 text-sm">{{ $rm->nama_pemilik }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ Str::limit($rm->anamnesa, 30) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ Str::limit($rm->diagnosa, 30) }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('perawat.rekam-medis.detail', $rm->idrekam_medis) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                    Detail & Tindakan
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
.min-h-20 {
    min-height: 5rem;
}

.calendar-day {
    transition: all 0.2s ease;
    cursor: pointer;
}

.calendar-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Responsive design untuk kalender */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 3rem;
        padding: 0.25rem;
    }
    
    .calendar-day .text-sm {
        font-size: 0.75rem;
    }
    
    .calendar-day .text-xs {
        font-size: 0.65rem;
    }
}
</style>
@endsection