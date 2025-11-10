<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard RSHP')</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-pnfgRRd/zz7GJ4SzjXZkkf3gqK4D8T1zsrHUb4F1HkKbm4L/lSokLfGmbJhIY6zMvuYDPnSX9l+5yNf/2FiUBA=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">
  {{-- Navbar --}}
  @include('layouts.lte.navbar')

  {{-- Konten --}}
  <main class="flex-1 container mx-auto px-6 py-6">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="text-center text-sm text-gray-500 py-4 border-t mt-6">
    &copy; {{ date('Y') }} Rumah Sakit Hewan Pendidikan. All rights reserved.
  </footer>
</body>

</html>