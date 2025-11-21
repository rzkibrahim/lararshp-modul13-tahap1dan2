@extends('layouts.lte.resepsionis')

@section('title', 'Temu Dokter - RSHP')

@section('content')
<div class="container-fluid py-4">
    <!-- Notification Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle fa-lg me-3"></i>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold">Berhasil!</h6>
                <span class="small">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold">Terjadi Kesalahan!</h6>
                <span class="small">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card bg-gradient-primary rounded-3 p-4 text-white shadow">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2 fw-bold">
                            <i class="fas fa-calendar-check me-2"></i>
                            Dashboard Antrian Dokter
                        </h2>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user-nurse me-1"></i>
                            Selamat datang di dashboard resepsionis - Kelola antrian temu dokter dengan mudah
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="date-display bg-white bg-opacity-10 rounded-2 p-3 d-inline-block">
                            <div class="fw-bold fs-5">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</div>
                            <div class="fs-6">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-warning bg-opacity-10 rounded-3 p-3 border-start border-warning border-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning rounded-2 p-3 me-3">
                        <i class="fas fa-clock fa-lg text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">{{ $antrian->where('status', 0)->count() }}</h4>
                        <small class="text-muted">Menunggu</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-success bg-opacity-10 rounded-3 p-3 border-start border-success border-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success rounded-2 p-3 me-3">
                        <i class="fas fa-check fa-lg text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">{{ $antrian->where('status', 1)->count() }}</h4>
                        <small class="text-muted">Selesai</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-danger bg-opacity-10 rounded-3 p-3 border-start border-danger border-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-danger rounded-2 p-3 me-3">
                        <i class="fas fa-times fa-lg text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">{{ $antrian->where('status', 2)->count() }}</h4>
                        <small class="text-muted">Dibatalkan</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-info bg-opacity-10 rounded-3 p-3 border-start border-info border-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info rounded-2 p-3 me-3">
                        <i class="fas fa-paw fa-lg text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">{{ $pets->count() }}</h4>
                        <small class="text-muted">Pet Tersedia</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Forms -->
        <div class="col-lg-5 mb-4">
            <!-- Filter Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-filter me-2"></i>
                        Filter Antrian
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" id="filterForm">
                        <input type="hidden" name="tanggal_daftar" value="{{ $selectedDaftarDate }}">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Lihat Antrian Tanggal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </span>
                                    <input type="date" id="date" name="date" class="form-control border-start-0" 
                                           value="{{ $selectedDate }}">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Registration Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0 fw-bold text-success">
                        <i class="fas fa-plus-circle me-2"></i>
                        Pendaftaran Antrian Baru
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('resepsionis.temu-dokter.store') }}" id="daftarForm">
                        @csrf
                        <div class="row g-3">
                            <!-- Tanggal Pendaftaran -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tanggal Pendaftaran</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                    </span>
                                    <input type="date" name="tanggal_daftar" id="tanggalDaftar" 
                                           class="form-control" value="{{ $selectedDaftarDate }}"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pilih tanggal untuk melihat pet yang tersedia
                                </small>
                            </div>

                            <!-- Pilih Pet -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pilih Pet</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-paw text-success"></i>
                                    </span>
                                    <select name="idpet" class="form-select" required id="petSelect">
                                        <option value="">— Pilih Pet —</option>
                                        @foreach($pets as $pet)
                                            <option value="{{ $pet->idpet }}" 
                                                    data-pemilik="{{ $pet->pemilik->user->nama ?? 'N/A' }}">
                                                {{ $pet->nama }} — Pemilik: {{ $pet->pemilik->user->nama ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-paw me-1"></i>
                                    Pet yang tersedia pada tanggal: {{ \Carbon\Carbon::parse($selectedDaftarDate)->format('d/m/Y') }}
                                </small>
                            </div>

                            <!-- Pilih Dokter -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pilih Dokter</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user-md text-info"></i>
                                    </span>
                                    <select name="idrole_user" class="form-select" required id="doctorSelect">
                                        <option value="">— Pilih Dokter —</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->idrole_user }}">
                                                Dr. {{ $doctor->user->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="col-12">
                                <div class="preview-section bg-light rounded-2 p-3 mt-2 d-none" id="previewData">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fas fa-clipboard-check me-1 text-primary"></i>
                                        Konfirmasi Pendaftaran
                                    </h6>
                                    <div class="row small">
                                        <div class="col-4">
                                            <strong class="text-muted">Tanggal:</strong>
                                            <div id="previewTanggal" class="fw-semibold text-dark"></div>
                                        </div>
                                        <div class="col-4">
                                            <strong class="text-muted">Pet:</strong>
                                            <div id="previewPet" class="fw-semibold text-dark"></div>
                                        </div>
                                        <div class="col-4">
                                            <strong class="text-muted">Dokter:</strong>
                                            <div id="previewDokter" class="fw-semibold text-dark"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold mt-2" id="daftarBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Daftarkan Antrian
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Queue List -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-secondary">
                        <i class="fas fa-list-ol me-2"></i>
                        Daftar Antrian - {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                    </h6>
                    <span class="badge bg-primary rounded-pill fs-6">
                        {{ $antrian->count() }} Antrian
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($antrian->count() === 0)
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted fw-semibold">Tidak ada antrian</h5>
                                <p class="text-muted mb-4">Belum ada antrian pada tanggal yang dipilih</p>
                                <button class="btn btn-outline-primary" onclick="scrollToForm()">
                                    <i class="fas fa-plus me-1"></i>Buat Antrian Baru
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="table-container">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">No. Urut</th>
                                        <th>Waktu Daftar</th>
                                        <th>Pet & Pemilik</th>
                                        <th>Dokter</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($antrian as $row)
                                        @php
                                            $status_val = (int)$row->status;
                                            $status_config = [
                                                0 => ['class' => 'bg-warning text-dark', 'text' => 'Menunggu', 'icon' => 'clock'],
                                                1 => ['class' => 'bg-success', 'text' => 'Selesai', 'icon' => 'check'],
                                                2 => ['class' => 'bg-danger', 'text' => 'Batal', 'icon' => 'times']
                                            ];
                                            $status = $status_config[$status_val] ?? $status_config[0];
                                            $waktu_display = \Carbon\Carbon::parse($row->waktu_daftar)->format('H:i');
                                        @endphp
                                        <tr class="queue-row">
                                            <td class="ps-4">
                                                <div class="queue-number fw-bold text-primary fs-5">
                                                    #{{ $row->no_urut }}
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted d-block">Terdaftar</small>
                                                <span class="fw-semibold">{{ $waktu_display }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">
                                                    <i class="fas fa-paw me-1 text-muted"></i>
                                                    {{ $row->nama_pet }}
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $row->nama_pemilik ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">
                                                    <i class="fas fa-user-md me-1 text-muted"></i>
                                                    {{ $row->nama_dokter }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $status['class'] }} py-2 px-3">
                                                    <i class="fas fa-{{ $status['icon'] }} me-1"></i>
                                                    {{ $status['text'] }}
                                                </span>
                                            </td>
                                            <td class="text-center pe-4">
                                                @if($status_val === 0)
                                                    <div class="action-buttons">
                                                        <form method="POST" action="{{ route('resepsionis.temu-dokter.update-status') }}" 
                                                              class="d-inline-block me-1">
                                                            @csrf
                                                            <input type="hidden" name="idreservasi_dokter" value="{{ $row->idreservasi_dokter }}">
                                                            <input type="hidden" name="status" value="1">
                                                            <button type="submit" class="btn btn-success btn-sm px-3" 
                                                                    data-bs-toggle="tooltip" title="Tandai Selesai">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('resepsionis.temu-dokter.update-status') }}" 
                                                              class="d-inline-block" 
                                                              onsubmit="return confirm('Yakin ingin membatalkan antrian ini?')">
                                                            @csrf
                                                            <input type="hidden" name="idreservasi_dokter" value="{{ $row->idreservasi_dokter }}">
                                                            <input type="hidden" name="status" value="2">
                                                            <button type="submit" class="btn btn-danger btn-sm px-3" 
                                                                    data-bs-toggle="tooltip" title="Batalkan Antrian">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-muted fst-italic small">
                                                        <i class="fas fa-ban me-1"></i>Tidak ada aksi
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
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="fw-bold text-dark">Memproses Pendaftaran</h6>
                <p class="text-muted mb-0 small">Sedang menyimpan data antrian...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Update form ketika tanggal daftar berubah
        $('#tanggalDaftar').change(function() {
            $('#filterForm input[name="tanggal_daftar"]').val($(this).val());
            $('#filterForm').submit();
        });

        // Format tanggal untuk display
        $('input[type="date"]').each(function() {
            if (!$(this).val()) {
                $(this).val('{{ date("Y-m-d") }}');
            }
        });

        // Preview data sebelum submit
        function updatePreview() {
            const tanggal = $('#tanggalDaftar').val();
            const petText = $('#petSelect option:selected').text();
            const dokterText = $('#doctorSelect option:selected').text();
            
            if (tanggal && petText !== '— Pilih Pet —' && dokterText !== '— Pilih Dokter —') {
                $('#previewTanggal').text(formatDate(tanggal));
                $('#previewPet').text(petText.split(' — ')[0]);
                $('#previewDokter').text(dokterText);
                $('#previewData').removeClass('d-none');
            } else {
                $('#previewData').addClass('d-none');
            }
        }

        // Format tanggal untuk preview
        function formatDate(dateString) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', options);
        }

        // Event listeners untuk update preview
        $('#tanggalDaftar, #petSelect, #doctorSelect').change(updatePreview);

        // Loading indicator saat submit form
        $('#daftarForm').on('submit', function() {
            if (this.checkValidity()) {
                $('#loadingModal').modal('show');
            }
        });

        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-scroll to form function
        window.scrollToForm = function() {
            document.getElementById('daftarForm').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }
    });
</script>
@endpush

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .welcome-card {
        backdrop-filter: blur(10px);
    }
    
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .table-container {
        max-height: 600px;
        overflow-y: auto;
    }
    
    .queue-row {
        transition: background-color 0.2s ease;
    }
    
    .queue-row:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .queue-number {
        font-family: 'Courier New', monospace;
    }
    
    .action-buttons .btn {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .action-buttons .btn:hover {
        transform: scale(1.05);
    }
    
    .empty-state {
        padding: 3rem 2rem;
    }
    
    .preview-section {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    
    .input-group-text {
        border-right: none;
    }
    
    .form-control, .form-select {
        border-left: none;
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    
    /* Custom scrollbar for table */
    .table-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endsection