<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Resepsionis - RSHP')</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @stack('styles')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background-color: #2563eb;
            border-radius: 0 2px 2px 0;
        }

        .animate-fade-in {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        
        {{-- Header --}}
        <header class="bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <div>
                                <h1 class="text-xl font-bold tracking-tight">RSHP UNAIR</h1>
                                <p class="text-primary-100 text-sm">Rumah Sakit Hewan Pendidikan</p>
                            </div>
                        </div>
                    </div>

                    {{-- User Info & Actions --}}
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden md:block">
                            <p class="font-semibold text-white">{{ session('user_name', 'Resepsionis') }}</p>
                            <p class="text-primary-200 text-sm">Resepsionis</p>
                        </div>
                        
                        <div class="h-8 w-8 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold shadow-sm">
                            {{ substr(session('user_name', 'R'), 0, 1) }}
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button 
                                type="submit"
                                class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg flex items-center space-x-2 transition-all duration-200 shadow-sm backdrop-blur-sm border border-white/20 hover:border-white/30">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                                <span class="text-sm font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Layout --}}
        <div class="flex flex-1">
            
            {{-- Sidebar --}}
            <aside class="w-64 bg-white shadow-xl border-r border-gray-200 sidebar-transition">
                <div class="p-6">
                    {{-- Navigation --}}
                    <nav class="space-y-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Menu Resepsionis</h3>
                        
                        <a href="{{ route('resepsionis.dashboard') }}"
                           class="nav-item flex items-center space-x-3 px-3 py-3 rounded-xl transition-all duration-200 
                           {{ request()->routeIs('resepsionis.dashboard') 
                                ? 'bg-primary-50 text-primary-700 font-semibold active border border-primary-100' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-home text-primary-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('resepsionis.registrasi.pemilik') }}"
                           class="nav-item flex items-center space-x-3 px-3 py-3 rounded-xl transition-all duration-200 
                           {{ request()->routeIs('resepsionis.registrasi.pemilik') 
                                ? 'bg-primary-50 text-primary-700 font-semibold active border border-primary-100' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-user-plus text-primary-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Registrasi Pemilik</span>
                        </a>

                        <a href="{{ route('resepsionis.registrasi.pet') }}"
                           class="nav-item flex items-center space-x-3 px-3 py-3 rounded-xl transition-all duration-200 
                           {{ request()->routeIs('resepsionis.registrasi.pet') 
                                ? 'bg-primary-50 text-primary-700 font-semibold active border border-primary-100' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-paw text-primary-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Registrasi Pet</span>
                        </a>

                        <a href="{{ route('resepsionis.temu-dokter.index') }}"
                           class="nav-item flex items-center space-x-3 px-3 py-3 rounded-xl transition-all duration-200 
                           {{ request()->routeIs('resepsionis.temu-dokter') 
                                ? 'bg-primary-50 text-primary-700 font-semibold active border border-primary-100' 
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-calendar-check text-primary-600 text-sm"></i>
                            </div>
                            <span class="font-medium">Temu Dokter & Antrian</span>
                        </a>
                    </nav>

                    {{-- Quick Stats --}}
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Statistik Hari Ini</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-list-ol text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Total Antrian</span>
                                </div>
                                <span class="text-lg font-bold text-blue-600">{{ $totalAntrianHariIni ?? 0 }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg border border-orange-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                        <i class="fas fa-clock text-orange-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Menunggu</span>
                                </div>
                                <span class="text-lg font-bold text-orange-600">{{ $antrianMenunggu ?? 0 }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Selesai</span>
                                </div>
                                <span class="text-lg font-bold text-green-600">{{ $antrianSelesai ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 min-h-screen">
                <div class="container mx-auto px-8 py-8">
                    
                    {{-- Page Header --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
                                <p class="text-gray-600 mt-2 text-lg">@yield('page_description', 'Selamat datang di dashboard resepsionis')</p>
                            </div>
                            
                            {{-- Breadcrumb atau Action Buttons --}}
                            <div class="flex items-center space-x-3">
                                @yield('header_actions')
                            </div>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="mt-4 w-24 h-1.5 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full"></div>
                    </div>

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl shadow-sm flex items-start space-x-3 animate-fade-in">
                            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl shadow-sm flex items-start space-x-3 animate-fade-in">
                            <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-xl shadow-sm">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-orange-600 text-xs"></i>
                                </div>
                                <h4 class="text-orange-800 font-semibold">Perhatian</h4>
                            </div>
                            <ul class="text-orange-700 text-sm space-y-1 ml-9">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-center space-x-2">
                                        <i class="fas fa-circle text-orange-400 text-xs"></i>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Main Page Content --}}
                    <div class="animate-fade-in">
                        @yield('content')
                    </div>

                </div>
            </main>
        </div>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-200 py-6">
            <div class="container mx-auto px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-2 text-gray-600 mb-4 md:mb-0">
                        <i class="fas fa-heart text-primary-500"></i>
                        <span class="text-sm">&copy; {{ date('Y') }} Rumah Sakit Hewan Pendidikan UNAIR. All rights reserved.</span>
                    </div>
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <span class="flex items-center space-x-2">
                            <i class="fas fa-shield-alt text-primary-400"></i>
                            <span>Sistem Terjamin</span>
                        </span>
                        <span class="hidden md:inline">•</span>
                        <span class="flex items-center space-x-2">
                            <i class="fas fa-bolt text-primary-400"></i>
                            <span>Response Cepat</span>
                        </span>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    {{-- Scripts --}}
    <script>
        // Simple fade-in animation
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate-fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>