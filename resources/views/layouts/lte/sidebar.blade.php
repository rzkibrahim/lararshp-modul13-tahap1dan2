<aside id="sidebar"
       class="fixed md:relative bg-gray-800 text-gray-100 w-64 h-full md:translate-x-0 -translate-x-full transition-transform duration-300 z-50">
  <div class="p-4 border-b border-gray-700 text-center font-bold text-lg">
    Rumah Sakit Hewan
  </div>
  <nav class="p-4 space-y-2">
    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-700">
      <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
    </a>
    <a href="{{ route('admin.user.index') }}" class="block px-3 py-2 rounded hover:bg-gray-700">
      <i class="fas fa-users mr-2"></i> Data User
    </a>
    <a href="{{ route('admin.jenis-hewan.index') }}" class="block px-3 py-2 rounded hover:bg-gray-700">
      <i class="fas fa-paw mr-2"></i> Jenis Hewan
    </a>
  </nav>
</aside>
