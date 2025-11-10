<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard RSHP')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a2e0c6ef6c.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 text-gray-800 flex">

  {{-- Sidebar --}}
  @include('layouts.lte.sidebar')

  <div class="flex-1 flex flex-col min-h-screen">
    {{-- Navbar --}}
    @include('layouts.lte.navbar')

    {{-- Content --}}
    <main class="flex-1 p-6">
      @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.lte.footer')
  </div>

  {{-- Sidebar toggle (mobile) --}}
  <script>
    document.getElementById('menu-toggle')?.addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });
  </script>
</body>
</html>
