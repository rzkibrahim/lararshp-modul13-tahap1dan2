@extends('layouts.lte.app')

@section('title', 'Data Kategori Klinis')
@section('page', 'Kategori Klinis')

@section('content')
<div class="max-w-7xl mx-auto">

  {{-- Notifikasi --}}
  @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
          <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
      </div>
  @endif

  @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
          <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
      </div>
  @endif

  {{-- Card --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4 flex justify-between items-center">
          <h2 class="text-xl font-semibold text-white">Data Kategori Klinis</h2>
          <a href="{{ route('admin.kategori-klinis.create') }}" 
             class="bg-white text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-50 transition duration-200">
              <i class="fas fa-plus mr-2"></i>Tambah Kategori Klinis
          </a>
      </div>

      {{-- Tabel --}}
      <div class="overflow-x-auto">
          <table class="min-w-full border-collapse">
              <thead>
                  <tr class="bg-gray-50">
                      <th class="p-4 text-left font-semibold text-gray-700">No</th>
                      <th class="p-4 text-left font-semibold text-gray-700">ID Kategori Klinis</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Nama Kategori Klinis</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($kategoriKlinis as $index => $item)
                  <tr class="border-b border-gray-200 hover:bg-blue-50 transition duration-150">
                      <td class="p-4 text-gray-600">{{ $index + 1 }}</td>
                      <td class="p-4 text-gray-800 font-medium">{{ $item->idkategori_klinis }}</td>
                      <td class="p-4 text-gray-800">{{ $item->nama_kategori_klinis }}</td>
                      <td class="p-4">
                          <div class="flex gap-2">
                              <a href="{{ route('admin.kategori-klinis.edit', $item->idkategori_klinis) }}" 
                                 class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-200">
                                  <i class="fas fa-edit mr-1"></i>Edit
                              </a>

                              {{-- Tombol Hapus / Kunci --}}
                              @if($item->jumlah_tindakan == 0)
                                  <form action="{{ route('admin.kategori-klinis.destroy', $item->idkategori_klinis) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori klinis ini?')">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" 
                                              class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-200">
                                          <i class="fas fa-trash mr-1"></i>Hapus
                                      </button>
                                  </form>
                              @else
                                  <button disabled 
                                          class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" 
                                          title="Kategori sedang digunakan">
                                      <i class="fas fa-lock mr-1"></i>Terkunci
                                  </button>
                              @endif
                          </div>
                      </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="4" class="p-8 text-center text-gray-500">
                          <i class="fas fa-database text-4xl mb-4 text-gray-300"></i>
                          <p class="text-lg">Tidak ada data kategori klinis</p>
                          <a href="{{ route('admin.kategori-klinis.create') }}" 
                             class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                              Tambah data pertama
                          </a>
                      </td>
                  </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
  </div>
</div>
@endsection
