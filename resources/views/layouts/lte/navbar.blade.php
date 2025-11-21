<nav class="flex justify-end items-center bg-white shadow px-8 py-3 sticky top-0 z-50 font-medium text-[15px]">
  <div class="flex space-x-5 items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
      <i class="fas fa-home mr-1.5"></i> Dashboard
    </a>

    <span class="text-gray-400">/</span>

    <div class="relative group">
      <button class="text-blue-600 hover:text-blue-800 flex items-center focus:outline-none">
        <i class="fas fa-database mr-1.5"></i> Data Master
        <i class="fas fa-chevron-down ml-1 text-xs"></i>
      </button>

      <div class="absolute right-0 mt-2 w-60 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-gray-200">
        <div class="py-2">
          <a href="{{ route('admin.jenis-hewan.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-paw mr-2"></i> Jenis Hewan
          </a>
          <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-tags mr-2"></i> Kategori
          </a>
          <a href="{{ route('admin.kategori-klinis.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-stethoscope mr-2"></i> Kategori Klinis
          </a>
          <a href="{{ route('admin.kode-tindakan-terapi.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-syringe mr-2"></i> Kode Tindakan & Terapi
          </a>
          <a href="{{ route('admin.pemilik.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-users mr-2"></i> Pemilik
          </a>
          <a href="{{ route('admin.pet.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-dog mr-2"></i> Pet
          </a>
          <a href="{{ route('admin.ras-hewan.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-dna mr-2"></i> Ras Hewan
          </a>
          <a href="{{ route('admin.role.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-user-tag mr-2"></i> Role
          </a>
          <a href="{{ route('admin.user.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <i class="fas fa-user mr-2"></i> User
          </a>
        </div>
      </div>
    </div>

    <span class="text-gray-400">/</span>
    <span class="text-gray-700 font-semibold">@yield('page')</span>
  </div>
</nav>