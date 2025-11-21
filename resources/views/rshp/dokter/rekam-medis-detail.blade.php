@extends('layouts.lte.dokter')

@section('title', 'Detail Rekam Medis - Dokter')
@section('page_title', 'Detail Rekam Medis')
@section('page_description', 'Lihat detail rekam medis')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-xl font-semibold text-gray-900">Detail Rekam Medis #{{ $rekamMedis->idrekam_medis }}</h5>
                    <p class="text-gray-500 text-sm mt-1">Informasi lengkap pemeriksaan</p>
                </div>
                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Informasi Pet & Pemilik -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Pet -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-white">
                        <h6 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <i class="fas fa-paw text-blue-600"></i>
                            <span>Informasi Pet</span>
                        </h6>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Nama Pet</span>
                                <span class="text-sm text-gray-900 font-semibold">{{ $rekamMedis->nama_pet }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Jenis Hewan</span>
                                <span class="text-sm text-gray-900">{{ $rekamMedis->nama_jenis_hewan }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Ras</span>
                                <span class="text-sm text-gray-900">{{ $rekamMedis->nama_ras }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pemilik -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-white">
                        <h6 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <i class="fas fa-user text-blue-600"></i>
                            <span>Informasi Pemilik</span>
                        </h6>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Nama Pemilik</span>
                                <span class="text-sm text-gray-900 font-semibold">{{ $rekamMedis->nama_pemilik }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pemeriksaan -->
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-white">
                    <h6 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-stethoscope text-blue-600"></i>
                        <span>Informasi Pemeriksaan</span>
                    </h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">Tanggal Pemeriksaan</label>
                                <div class="flex items-center space-x-2 text-gray-900">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">Temuan Klinis</label>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 min-h-[80px]">
                                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $rekamMedis->temuan_klinis ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">Anamnesa</label>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 min-h-[80px]">
                                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $rekamMedis->anamnesa ?: '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 block mb-2">Diagnosa</label>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 min-h-[80px]">
                                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $rekamMedis->diagnosa ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons untuk Tindakan -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h6 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <i class="fas fa-cogs text-blue-600"></i>
                            <span>Kelola Tindakan & Terapi</span>
                        </h6>
                        <a href="{{ route('dokter.detail-rekam-medis.create', $rekamMedis->idrekam_medis) }}"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Tindakan</span>
                        </a>
                    </div>
                </div>

                @if($detailRekamMedis->isNotEmpty())
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi Tindakan/Terapi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Tindakan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Tambahan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($detailRekamMedis as $detail)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $detail->kode }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $detail->deskripsi_tindakan_terapi }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $detail->nama_kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ $detail->nama_kategori_klinis }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if(!empty($detail->detail))
                                        <span class="whitespace-pre-wrap">{{ $detail->detail }}</span>
                                        @else
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('dokter.detail-rekam-medis.edit', [$rekamMedis->idrekam_medis, $detail->iddetail_rekam_medis]) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete({{ $rekamMedis->idrekam_medis }}, {{ $detail->iddetail_rekam_medis }})"
                                                class="text-red-600 hover:text-red-900 transition-colors" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="p-6 text-center">
                    <div class="text-gray-500 flex flex-col items-center">
                        <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg">Belum ada tindakan atau terapi</p>
                        <p class="text-sm mt-2">Klik tombol "Tambah Tindakan" untuk menambahkan tindakan atau terapi</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 bg-gray-50 no-print">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('dokter.rekam-medis.list') }}"
                    class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 px-6 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 w-full sm:w-auto justify-center">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
                <button onclick="confirmSelesai({{ $rekamMedis->idrekam_medis }})"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2 w-full sm:w-auto justify-center">
                    <i class="fas fa-check-circle"></i>
                    <span>Selesai</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmDelete(rekamMedisId, detailId) {
        console.log('Delete clicked:', rekamMedisId, detailId);

        if (typeof Swal === 'undefined') {
            alert('Error: SweetAlert2 tidak terload. Silakan refresh halaman.');
            return;
        }

        Swal.fire({
            title: 'Hapus Tindakan?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/dokter/rekam-medis/${rekamMedisId}/detail/${detailId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Delete response:', data);
                        Swal.close();

                        if (data.success) {
                            Swal.fire(
                                'Terhapus!',
                                'Tindakan berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Gagal menghapus data',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        Swal.close();
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data: ' + error.message,
                            'error'
                        );
                    });
            }
        });
    }

    function confirmSelesai(rekamMedisId) {
        console.log('Selesai clicked:', rekamMedisId);

        if (typeof Swal === 'undefined') {
            alert('Error: SweetAlert2 tidak terload. Silakan refresh halaman.');
            return;
        }

        Swal.fire({
            title: 'Selesaikan Rekam Medis?',
            html: `
                <div class="text-left">
                    <p class="mb-3">Apakah Anda yakin ingin menyelesaikan rekam medis ini?</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span>Rekam medis yang sudah selesai akan diarsipkan dan tidak dapat diubah lagi.</span>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Hijau
            cancelButtonColor: '#3b82f6',  // Biru
            confirmButtonText: '<i class="fas fa-check-circle mr-2"></i>Ya, Selesaikan',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            reverseButtons: true,
            focusConfirm: false,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menyelesaikan...',
                    text: 'Sedang menyelesaikan rekam medis',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX request untuk menyelesaikan rekam medis
                fetch(`/dokter/rekam-medis/${rekamMedisId}/selesai`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Selesai response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Selesai response:', data);
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Rekam medis berhasil diselesaikan.',
                            icon: 'success',
                            confirmButtonColor: '#10b981',
                            confirmButtonText: '<i class="fas fa-check mr-2"></i>OK'
                        }).then(() => {
                            // Redirect ke halaman list rekam medis
                            window.location.href = '{{ route("dokter.rekam-medis.list") }}';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Gagal menyelesaikan rekam medis',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: '<i class="fas fa-times mr-2"></i>Tutup'
                        });
                    }
                })
                .catch(error => {
                    console.error('Selesai error:', error);
                    Swal.close();
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyelesaikan rekam medis: ' + error.message,
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: '<i class="fas fa-times mr-2"></i>Tutup'
                    });
                });
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .bg-white {
            background: white !important;
        }

        .shadow-lg {
            box-shadow: none !important;
        }

        .border {
            border: 1px solid #e5e7eb !important;
        }

        .rounded-2xl,
        .rounded-xl,
        .rounded-lg {
            border-radius: 0 !important;
        }
    }

    /* Custom styling untuk SweetAlert */
    .swal2-popup {
        border-radius: 12px !important;
    }
</style>
@endpush