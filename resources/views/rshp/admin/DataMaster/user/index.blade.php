@extends('layouts.lte.app')

@section('title', 'Data User')
@section('page', 'User')

@section('content')
<div class="max-w-6xl mx-auto">
  {{-- Notifikasi sukses/error --}}
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

  {{-- Card Container --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4">
          <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold text-white">Data User</h2>
              <div class="flex items-center space-x-4">
                  <span class="text-blue-100">Total: {{ $users->count() }} user</span>
                  <a href="{{ route('admin.user.create') }}" 
                     class="bg-white text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-50 transition duration-200">
                      <i class="fas fa-plus mr-2"></i>Tambah Data
                  </a>
              </div>
          </div>
      </div>

      {{-- Tabel Data --}}
      <div class="overflow-x-auto">
          <table class="min-w-full border-collapse">
              <thead>
                  <tr class="bg-gray-50">
                      <th class="p-4 text-left font-semibold text-gray-700">No</th>
                      <th class="p-4 text-left font-semibold text-gray-700">ID User</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Nama</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Email</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Role</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Status</th>
                      <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($users as $index => $user)
                  @php
                      $hasActiveRole = $user->roleUser->where('status', 1)->isNotEmpty();
                  @endphp
                  <tr class="border-b border-gray-200 hover:bg-blue-50 transition duration-150">
                      <td class="p-4 text-gray-600">{{ $index + 1 }}</td>
                      <td class="p-4 text-gray-800 font-medium">{{ $user->iduser }}</td>
                      <td class="p-4 text-gray-800">{{ $user->nama }}</td>
                      <td class="p-4 text-gray-800">{{ $user->email }}</td>
                      <td class="p-4">
                          @if($user->roleUser && $user->roleUser->count() > 0)
                              <div class="flex flex-wrap gap-2">
                                  @foreach($user->roleUser as $ru)
                                      @if($ru->status == 1)
                                          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                              <i class="fas fa-check mr-1 text-xs"></i>
                                              {{ $ru->role->nama_role ?? 'Unknown' }}
                                          </span>
                                      @else
                                          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-300 line-through">
                                              <i class="fas fa-times mr-1 text-xs"></i>
                                              {{ $ru->role->nama_role ?? 'Unknown' }}
                                          </span>
                                      @endif
                                  @endforeach
                              </div>
                          @else
                              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                  Tidak ada role
                              </span>
                          @endif
                      </td>
                      <td class="p-4">
                          @if($hasActiveRole)
                              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                  <i class="fas fa-check-circle mr-1 text-xs"></i> Aktif
                              </span>
                          @else
                              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                  <i class="fas fa-times-circle mr-1 text-xs"></i> Tidak Aktif
                              </span>
                          @endif
                      </td>
                      <td class="p-4">
                          <div class="flex gap-2">
                              <a href="{{ route('admin.user.edit', $user->iduser) }}" 
                                 class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-200">
                                  <i class="fas fa-edit mr-1"></i>Edit
                              </a>
                              <form action="{{ route('admin.user.destroy', $user->iduser) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" 
                                          class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-200">
                                      <i class="fas fa-trash mr-1"></i>Hapus
                                  </button>
                              </form>
                          </div>
                      </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="7" class="p-8 text-center text-gray-500">
                          <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                          <p class="text-lg">Tidak ada data user</p>
                          <a href="{{ route('admin.user.create') }}" 
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
